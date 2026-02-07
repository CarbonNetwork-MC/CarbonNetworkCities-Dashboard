<?php

namespace App\Livewire\Admin\CoC;

use App\Models\CoCType;
use Livewire\Component;

class NewCoCType extends Component
{
    public $name;
    public $description;

    public function createCoCType() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:coc_types,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        CoCType::create([
            'name' => ucwords($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.coc.render')->success(__('admin.toast.coc.created'));
    }

    public function render()
    {
        return view('livewire.admin.coc.new-coc-type');
    }
}
