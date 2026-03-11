<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use App\Models\CompanyItem;
use App\Models\CompanySale;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryUpdate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class SalesOverview extends Component
{
    use WithPagination;

    public $company;
    public $products;

    public $hasPermission;

    public $salesPerPage = 10;

    public $saleToDelete;
    public $showDeleteSaleModal = false;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->with(['country'])->first();
        $this->products = CompanyItem::where('company_id', $this->company->id)->with(['stock', 'item'])->get();

        $user = Auth::user();
        $this->hasPermission = $user->hasRole('Superadmin')
            || $user->player->uuid == $this->company->owner_uuid
            || $this->company->employees()->where('player_uuid', $user->player->uuid)->first()->role == 'manager';
    }

    public function removeSale($id) {
        $this->saleToDelete = CompanySale::where('id', $id)->where('company_id', $this->company->id)->first();
        $this->showDeleteSaleModal = true;
    }

    public function destroySale() {
        if (!$this->saleToDelete) return;

        foreach ($this->saleToDelete->items as $saleItem) {
            $companyItem = CompanyItem::where('company_id', $this->company->id)->where('id', $saleItem->item_id)->first();
            if ($companyItem) {
                $companyItem->stock->quantity += $saleItem->quantity;
                $companyItem->stock->save();
            }
        }

        $salary = EmployeeSalary::where('player_uuid', $this->saleToDelete->employee_uuid)
            ->where('company_id', $this->company->id)
            ->where('status', 'unpaid')
            ->first();
        if ($salary) {
            $update = EmployeeSalaryUpdate::where('sale_id', $this->saleToDelete->id)->first();
            if ($update) {
                $salary->amount -= $update->amount;
                $salary->save();
                $update->delete();
            }
        }

        $this->saleToDelete->delete();

        $this->reset(['saleToDelete', 'showDeleteSaleModal']);

        Toaster::success(__('company.toasts.sale_deleted'));
    }

    public function render()
    {
        $sales = CompanySale::where('company_id', $this->company->id)
            ->where('week', now()->weekOfYear)
            ->with(['items', 'customer', 'employee', 'salary'])
            ->paginate($this->salesPerPage, ['*'], 'sales-page');

        return view('livewire.company.sales.sales-overview', [
            'sales' => $sales,
        ]);
    }
}
