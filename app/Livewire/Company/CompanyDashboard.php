<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyBankaccount;
use App\Models\CompanyNotification;
use App\Models\CompanyOrder;
use App\Models\CompanyStock;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class CompanyDashboard extends Component
{
    use WithPagination;

    public $company;

    public $bestProduct;
    public $worstProduct;
    public $weeklyRevenue;
    public $weeklyOrders;
    public $mostValuableCustomer;

    public $hasPermission = false;

    public function mount($companyId) {
        $this->company = Company::find($companyId);

        $this->bestProduct = $this->getBestProduct();
        $this->worstProduct = $this->getWorstProduct();
        $this->weeklyRevenue = $this->getWeeklyRevenue();
        $this->weeklyOrders = $this->getWeeklyOrders();
        $this->mostValuableCustomer = $this->getMostValuableCustomer();

        $user = Auth::user();
        $this->hasPermission = $user->hasRole('Superadmin')
            || $user->player->uuid == $this->company->owner_uuid
            || $this->company->employees()->where('player_uuid', $user->player->uuid)->first()->role == 'manager';

        // Create notifications for low stock items
        $this->createStockNotifications();
    }

    public function isNotificationRead($id) {
        $notification = CompanyNotification::find($id);
        if ($notification && !$notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }

        Toaster::success(__('company.toasts.notification_marked_as_read'));
    }

    public function render()
    {
        $companyStock = CompanyStock::where('company_id', $this->company->id)
            ->with('item.item')
            ->paginate(9, ['*'], 'stock_page');

        $employees = Employee::where('company_id', $this->company->id)
            ->with('player')
            ->orderByRaw("
                CASE role
                    WHEN 'manager' THEN 1
                    WHEN 'employee' THEN 2
                    ELSE 3
                END
            ")
            ->paginate(7, ['*'], 'employees_page');

        $bankAccounts = CompanyBankaccount::where('company_id', $this->company->id)
            ->with('country')
            ->paginate(3, ['*'], 'bank_accounts_page');

        $notifications = CompanyNotification::where('company_id', $this->company->id)
            ->where('is_read', false)
            ->orderByRaw("
                CASE level
                    WHEN 'info' THEN 1
                    WHEN 'critical' THEN 2
                    WHEN 'warning' THEN 3
                    ELSE 4
                END
            ")
            ->paginate(5, ['*'], 'notifications_page');

        $orders = CompanyOrder::where('company_id', $this->company->id)
            ->where('completed', false)
            ->with('order', 'order.items', 'order.items.item')
            ->limit(2)
            ->get();
        $orderTotal = CompanyOrder::where('company_id', $this->company->id)
            ->where('completed', false)
            ->with('order', 'order.items', 'order.items.item')
            ->count();

        return view('livewire.company.company-dashboard', [
            'companyStock' => $companyStock,
            'employees' => $employees,
            'bankAccounts' => $bankAccounts,
            'notifications' => $notifications,
            'orders' => $orders,
            'orderTotal' => $orderTotal,
        ]);
    }

    private function createStockNotifications() {
        $companyStock = CompanyStock::where('company_id', $this->company->id)
            ->where(function ($query) {
                $query->whereColumn('quantity', '<=', 'critical_threshold')
                    ->orWhereColumn('quantity', '<=', 'warning_threshold');
            })
            ->with('item.item')
            ->get();

        foreach ($companyStock as $stock) {
            // Skip if notification already exists for this item and level
            if (CompanyNotification::where('company_id', $this->company->id)
                ->where('item_id', $stock->item_id)
                ->where('type', 'stock')
                ->where('level', $stock->quantity <= $stock->critical_threshold ? 'critical' : 'warning')
                ->where('is_read', false)
                ->exists()) {
                continue;
            }

            $type = $stock->quantity <= $stock->critical_threshold ? 'critical' : 'warning';
            $message = $type == 'critical'
                ? __('company.notifications.stock_critical', ['item' => $stock->item->item->name])
                : __('company.notifications.stock_warning', ['item'=> $stock->item->item->name]);

            CompanyNotification::create([
                'company_id' => $this->company->id,
                'item_id' => $stock->item_id,
                'type' => 'stock',
                'level' => $type,
                'message' => $message,
                'is_read' => false,
            ]);
        }
    }

    private function getBestProduct() {
        return $this->company->sales()
            ->with(['items.item.item'])
            ->get()
            ->flatMap(function ($sale) {
                return $sale->items;
            })
            ->groupBy('item_id')
            ->map(function ($items, $itemId) {
                return [
                    'item_id' => $itemId,
                    'name' => $items->first()->item->item->name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum(function ($item) {
                        return $item->price;
                    }),
                ];
            })
            ->sortByDesc('quantity')
            ->first();
    }

    private function getWorstProduct() {
        return $this->company->sales()
            ->with(['items.item.item'])
            ->get()
            ->flatMap(function ($sale) {
                return $sale->items;
            })
            ->groupBy('item_id')
            ->map(function ($items, $itemId) {
                return [
                    'item_id' => $itemId,
                    'name' => $items->first()->item->item->name,
                    'quantity' => $items->sum('quantity'),
                    'revenue' => $items->sum(function ($item) {
                        return $item->price;
                    }),
                ];
            })
            ->sortBy('quantity')
            ->first();
    }

    private function getWeeklyRevenue() {
        return $this->company->sales()
            ->where('created_at', '>=', now()->subWeek())
            ->sum('total_revenue');
    }

    private function getWeeklyOrders() {
        return $this->company->sales()
            ->where('created_at', '>=', now()->subWeek())
            ->count();
    }

    private function getMostValuableCustomer() {
        return $this->company->sales()
            ->where('created_at', '>=', now()->subMonth())
            ->with('customer')
            ->get()
            ->groupBy('customer_uuid')
            ->map(function ($sales, $customerUuid) {
                return [
                    'customer_uuid' => $customerUuid,
                    'name' => $sales->first()->customer->username,
                    'total_spent' => $sales->sum('total_revenue'),
                ];
            })
            ->sortByDesc('total_spent')
            ->first();
    }
}
