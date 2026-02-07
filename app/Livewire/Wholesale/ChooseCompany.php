<?php

namespace App\Livewire\Wholesale;

use Livewire\Component;

class ChooseCompany extends Component
{
    public $companies;

    public function mount() {
        $player = auth()->user()->player;
        $amountOfCompanies = $player->amountOfCompanies();

        if ($amountOfCompanies === 0) {
            return redirect()->route('dashboard.render');
        } else if ($amountOfCompanies === 1) {
            $company = $player->companies()->first() 
                ?? $player->managerAt()->first()->company;
            return redirect()->route('wholesale.create-order', ['companyId' => $company->id]);
        }

        $this->companies = $player->companies()->get()->merge($player->managerAt()->get());
    }

    public function selectCompany($companyId) {
        return redirect()->route('wholesale.create-order', ['companyId' => $companyId]);
    }

    public function render()
    {         
        return view('livewire.wholesale.choose-company');
    }
}
