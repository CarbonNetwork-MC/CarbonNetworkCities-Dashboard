<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;

class StockOverview extends Component
{
    public $company;

    public function mount($companyId) {
        $this->company = Company::with(['items', 'stock'])->findOrFail($companyId);
    }

    public function render()
    {
        return view('livewire.company.stock-overview');
    }
}
