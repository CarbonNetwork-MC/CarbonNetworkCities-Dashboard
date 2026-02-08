<?php

namespace App\Livewire\Wholesale;

use App\Models\Company;
use Livewire\Component;

class CreateOrder extends Component
{
    public $company;

    public function mount($companyId) {
        $this->company = Company::findOrFail($companyId);
    }
    
    public function render()
    {
        return view('livewire.wholesale.create-order');
    }
}
