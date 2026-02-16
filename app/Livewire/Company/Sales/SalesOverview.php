<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use App\Models\CompanyItem;
use Livewire\Component;

class SalesOverview extends Component
{
    public $company;

    public $currentYear;
    public $currentWeek;

    public $products;
    public $sales = [];

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->with(['sales', 'sales.items'])->first();

        $this->currentYear = now()->year;
        $this->currentWeek = now()->weekOfYear;

        $this->products = CompanyItem::where('company_id', $this->company->id)->with(['stock', 'item'])->get();
    }

    public function render()
    {
        return view('livewire.company.sales.sales-overview');
    }
}
