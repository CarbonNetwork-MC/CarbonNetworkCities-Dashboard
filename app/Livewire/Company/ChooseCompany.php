<?php

namespace App\Livewire\Company;

use Livewire\Component;

class ChooseCompany extends Component
{
    public $companies;

    public function mount() {
        $companies = collect();
    
        $ownedCompanies = auth()->user()->player->companies;
        $employeeCompanies = auth()->user()->player->employeeAt->load('company')->pluck('company');

        $companies = $ownedCompanies->merge($employeeCompanies)->unique('id');
        $this->companies = $companies;
    }

    public function render()
    {
        return view('livewire.company.choose-company');
    }
}
