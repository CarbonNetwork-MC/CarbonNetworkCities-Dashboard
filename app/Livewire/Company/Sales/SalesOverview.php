<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use App\Models\CompanyItem;
use App\Models\CompanySale;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SalesOverview extends Component
{
    use WithPagination;

    public $company;
    public $products;

    public $hasPermission;

    public $salesPerPage = 10;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->with(['country'])->first();
        $this->products = CompanyItem::where('company_id', $this->company->id)->with(['stock', 'item'])->get();

        $user = Auth::user();
        $this->hasPermission = $user->hasRole('Superadmin')
            || $user->player->uuid == $this->company->owner_uuid
            || $this->company->employees()->where('player_uuid', $user->player->uuid)->first()->role == 'manager';
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
