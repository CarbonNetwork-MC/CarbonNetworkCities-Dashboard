<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;

class CompanyDashboard extends Component
{
    public $company;

    public function mount($companyId) {
        $this->company = Company::find($companyId);
    }

    public function render()
    {
        return view('livewire.company.company-dashboard');
    }
}
