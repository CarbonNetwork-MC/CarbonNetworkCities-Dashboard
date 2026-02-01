<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\CoCType;
use App\Models\ItemGroup;
use Livewire\Component;

class NewItemGroup extends Component
{
    public $cocTypes;

    public $name;
    public $cocType;
    public $sellable = false;

    public function mount() {
        $this->cocTypes = CoCType::get(['id', 'name']);
    }

    public function createItemGroup() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'cocType' => ['required', 'string', 'exists:coc_types,id'],
        ]);

        $itemGroup = ItemGroup::create([
            'name' => $data['name'],
            'coc_type' => $data['cocType'],
            'sellable' => $this->sellable,
        ]);

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.itemsmenu.item_group_created'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.new-item-group');
    }
}
