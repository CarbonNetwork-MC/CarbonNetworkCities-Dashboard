<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderDetails extends Component
{
    public $company;
    public $order;

    public function mount($companyId, $orderId) {
        $this->company = Company::where('id', $companyId)->with('stock', 'stock.item')->first();
        $this->order = CompanyOrder::where('id', $orderId)->with('order', 'order.items', 'order.items.item')->first();
    }

    public function markAsCompleted() {
        $this->order->update([
            'completed' => true,
            'completed_by' => Auth::user()->player->uuid,
        ]);

        return redirect()->route('company.wholesale-orders.render', ['companyId' => $this->company->id])->success(__('company.toasts.order_marked_as_completed'));
    }

    public function markAsCompletedAndUpdateStock() {
        $this->order->update([
            'completed' => true,
            'completed_by' => Auth::user()->player->uuid,
            'stock_updated' => true,
        ]);

        foreach ($this->order->order->items as $orderItem) {
            foreach ($this->company->stock as $stockItem) {
                if ($stockItem->item->item_id === $orderItem->item_id) {
                    $stockItem->update([
                        'quantity' => $stockItem->quantity + $orderItem->amount,
                    ]);
                }
            }
        }
    }

    public function markAsUncompleted() {
        $revertStock = $this->order->stock_updated;

        $this->order->update([
            'completed' => false,
            'completed_by' => null,
            'stock_updated' => false,
        ]);

        // TODO: Revert stock changes if needed
        if ($revertStock) {
            foreach ($this->order->order->items as $orderItem) {
                foreach ($this->company->stock as $stockItem) {
                    if ($stockItem->item->item_id === $orderItem->item_id) {
                        $stockItem->update([
                            'quantity' => $stockItem->quantity - $orderItem->amount,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('company.wholesale-orders.render', ['companyId' => $this->company->id])->success(__('company.toasts.order_marked_as_uncompleted'));
    }

    public function render()
    {
        return view('livewire.company.order-details');
    }
}
