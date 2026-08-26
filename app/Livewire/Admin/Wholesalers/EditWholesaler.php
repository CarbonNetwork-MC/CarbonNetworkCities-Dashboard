<?php

namespace App\Livewire\Admin\Wholesalers;

use App\Models\Country;
use App\Models\Wholesaler;
use Livewire\Component;

class EditWholesaler extends Component
{
    public $wholesaler;
    public $employees;

    public $countries;

    public $countryId;
    public $name;

    public function mount($wholesalerId) {
        $this->wholesaler = Wholesaler::where('id', $wholesalerId)->firstOrFail();
        $this->countryId = $this->wholesaler->country_id;
        $this->name = $this->wholesaler->name;

        $this->employees = $this->wholesaler->employees;

        $this->countries = Country::get(['id', 'name']);
    }

    public function render()
    {
        return view('livewire.admin.wholesalers.edit-wholesaler');
    }
}
