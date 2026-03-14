<?php

namespace App\Livewire\Wholesale;

use App\Models\Wholesaler;
use Livewire\Component;

class Start extends Component
{
    public $companies;
    public $wholesalers;

    public $step = 1;
    public $numberOfSteps = 3;

    public $selectedCompany = null;

    public function mount($step, $companyId = null) {
        $this->step = $step;
        $this->selectedCompany = $companyId;

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
        $this->wholesalers = Wholesaler::all();
    }

    public function selectCompany($companyId) {
        $this->selectedCompany = $companyId;
        $this->step = 2;
    }

    public function selectWholesaler($wholesalerId) {
        return redirect()->route('wholesale.create-order', ['wholesalerId' => $wholesalerId, 'companyId' => $this->selectedCompany]);
    }

    public function decrementStep() {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function render()
    {         
        return view('livewire.wholesale.start');
    }
}
