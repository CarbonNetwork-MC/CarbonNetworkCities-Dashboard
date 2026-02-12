<?php

namespace App\Livewire\Admin\CoC;

use App\Models\CoCType;
use Livewire\Component;

class EditCoCType extends Component
{
    public $cocType;

    public $name;
    public $description;

    public function mount($id) {
        $this->cocType = CoCType::findOrFail($id);
        
        $this->name = $this->cocType->name;
        $this->description = $this->cocType->description;
    }

    public function updateCoCType() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:coc_types,name,' . $this->cocType->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->cocType->update([
            'name' => ucwords($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('admin.coc.render')->success(__('admin.toasts.coc.updated'));
    }

    public function render()
    {
        return view('livewire.admin.coc.edit-coc-type');
    }
}
