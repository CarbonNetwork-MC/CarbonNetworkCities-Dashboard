<?php

namespace App\Livewire\Company\Archive;

use App\Models\Company;
use App\Models\CompanySale;
use Livewire\Component;

class Details extends Component
{
    public $company;
    public $sale;

    public $customer;
    public $employee;

    public function mount($companyId, $saleId) {
        $this->company = Company::where('id', $companyId)->with(['country'])->first();
        $this->sale = CompanySale::where('id', $saleId)->with(['customer', 'employee', 'items.item'])->first();
    
        $this->customer = $this->sale->customer->username;
        $this->employee = $this->sale->employee->username;
    }

    public function render()
    {
        return view('livewire.company.archive.details');
    }
}
