<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use Livewire\Component;

class SalesOverview extends Component
{
    public $company;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->with(['country'])->first();
    }

    public function render()
    {
        return view('livewire.company.sales.sales-overview');
    }
}
