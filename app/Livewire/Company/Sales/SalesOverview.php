<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use App\Models\CompanyItem;
use App\Models\CompanySale;
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

        $this->saleToDelete->delete();

        $this->reset(['saleToDelete', 'showDeleteSaleModal']);

        Toaster::success(__('company.toasts.sale_deleted'));
    }

    public function render()
    {
        $sales = CompanySale::where('company_id', $this->company->id)
            ->with(['items', 'customer', 'employee'])
            ->paginate($this->salesPerPage, ['*'], 'sales-page');

        return view('livewire.company.sales.sales-overview', [
            'sales' => $sales,
        ]);
    }
}
