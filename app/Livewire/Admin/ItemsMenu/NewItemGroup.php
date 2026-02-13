<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use App\Models\CoCType;
use Livewire\Component;
use App\Models\ItemGroup;

class NewItemGroup extends Component
{
    public $cocTypes;
    public $allItems;

    public $name;
    public $cocType;
    public $sellable = false;

    public array $items = [];

    public function mount() {
        $this->cocTypes = CoCType::get(['id', 'name']);
        $this->allItems = Item::get(['id', 'internal_id']);

        $this->items = [
            $this->emptyItem(),
        ];
    }

    public function createItemGroup() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'cocType' => ['required', 'string', 'exists:coc_types,id'],
            'items.*.item_id' => ['required', 'distinct', 'exists:items,id'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.base_price' => ['required', 'numeric', 'min:0'],
        ]);

        $itemGroup = ItemGroup::create([
            'name' => $data['name'],
            'coc_type' => $data['cocType'],
            'sellable' => $this->sellable,
        ]);

        foreach ($this->items as $item) {
            if (!$item['item_id']) {
                continue;
            }

            $itemGroup->items()->create([
                'item_id' => $item['item_id'],
                'price' => $item['price'] ?? 0,
                'base_price' => $item['base_price'] ?? 0,
                'sellable' => $item['sellable'],
            ]);
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toasts.itemsmenu.item_group_created'));
    }

    public function addItem() {
        $this->items[] = $this->emptyItem();
    }

    public function removeItem($index) {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function availableItemsFor($index) {
        $selectedIds = collect($this->items)->pluck('item_id')->filter()->values();

        if ($this->items[$index]['item_id']) {
            $selectedIds = $selectedIds->reject(
                fn($id) => $id === $this->items[$index]['item_id']
            );
        }

        return $this->allItems
            ->reject(fn ($item) => $selectedIds->contains($item->id))
            ->map(fn ($item) => [
                'value' => $item->id,
                'label' => $item->internal_id,
            ]);
    }

    public function emptyItem(): array {
        return [
            'item_id' => null,
            'price' => null,
            'base_price' => null,
            'sellable' => false,
        ];
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.new-item-group');
    }
}
