<?php

namespace App\Livewire\Wholesale;

use App\Models\Wholesaler;
use Livewire\Component;

class SelectWholesaler extends Component
{
    public $wholesalers;

    public function mount() {
        $this->wholesalers = Wholesaler::with('country:id,name')->get(['id', 'name', 'country_id']);
    }

    public function render()
    {
        return view('livewire.wholesale.select-wholesaler');
    }
}
