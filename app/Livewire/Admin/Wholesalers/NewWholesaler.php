<?php

namespace App\Livewire\Admin\Wholesalers;

use App\Models\Country;
use App\Models\Wholesaler;
use Livewire\Component;

class NewWholesaler extends Component
{
    public $countries;

    public $countryId;
    public $name;

    public function mount() {
        $this->countries = Country::get(['id', 'name']);
    }

    public function createWholesaler() {
        $data = $this->validate([
            'countryId' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
        ]);

        Wholesaler::create([
            'country_id' => $data['countryId'],
            'name' => $data['name'],
        ]);

        return redirect()->route('admin.wholesalers.render')->success(__('admin.toasts.wholesalers.add_success'));
    }

    public function render()
    {
        return view('livewire.admin.wholesalers.new-wholesaler');
    }
}
