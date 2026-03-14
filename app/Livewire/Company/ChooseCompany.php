<?php

namespace App\Livewire\Company;

use Livewire\Component;

class ChooseCompany extends Component
{
    public $companies;

    public function mount() {
        $companies = collect();
    
        $ownedCompanies = auth()->user()->player->companies;
        $managerCompanies = auth()->user()->player->managerAt;
        $employeeCompanies = auth()->user()->player->employeeAt;

        $companies = $ownedCompanies->merge($managerCompanies)->merge($employeeCompanies)->unique('id');
        $this->companies = $companies;
    }

    public function render()
    {
        return view('livewire.company.choose-company');
    }
}
