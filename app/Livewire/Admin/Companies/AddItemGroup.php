<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;

class AddItemGroup extends Component
{
    public $company;

    public function mount($id) {
        $this->company = Company::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.admin.companies.add-item-group');
    }
}
