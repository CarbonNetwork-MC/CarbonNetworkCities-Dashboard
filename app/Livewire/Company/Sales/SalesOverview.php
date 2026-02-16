<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use Livewire\Component;

class SalesOverview extends Component
{
    public $company;

    public $currentYear;
    public $currentWeek;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->with(['sales', 'sales.items'])->first();

        $this->currentYear = now()->year;
        $this->currentWeek = now()->weekOfYear;
    }

    public function render()
    {
        return view('livewire.company.sales.sales-overview');
    }
}
