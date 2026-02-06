<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use App\Models\CoCType;
use Livewire\Component;
use App\Models\ItemGroup;
use Illuminate\Support\Facades\DB;

class EditItemGroup extends Component
{
    public $itemGroup;

    public $cocTypes;
    public $allItems;

    public $name;
    public $cocType;
    public $sellable = false;

    public array $items = [];

    public function mount($id) {
        $this->itemGroup = ItemGroup::find($id);

        $this->cocTypes = CoCType::get(['id', 'name']);
        $this->allItems = Item::get(['id', 'internal_id']);

        $this->name = $this->itemGroup->name;
        $this->cocType = $this->itemGroup->coc_type;
        $this->sellable = (bool) $this->itemGroup->sellable;

        $this->items = $this->itemGroup->items->map(function ($item) {
            return [
                'item_id' => $item->id,
                'price' => $item->pivot->price,
                'base_price' => $item->pivot->base_price,
                'sellable' => (bool) $item->pivot->sellable,
            ];
        })->toArray();
    }

    public function updateItemGroup() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'cocType' => ['required', 'string', 'exists:coc_types,id'],
            'items.*.item_id' => ['required', 'distinct', 'exists:items,id'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.base_price' => ['required', 'numeric', 'min:0'],
        ]);

        $this->itemGroup->update([
            'name' => $data['name'],
            'coc_type' => $data['cocType'],
            'sellable' => $this->sellable,
        ]);

        // Sync items
        $syncData = collect($this->items)
            ->filter(fn ($item) => filled($item['item_id']))
            ->mapWithKeys(fn ($item) => [
                $item['item_id'] => [
                    'price' => $item['price'] ?? 0,
                    'base_price' => $item['base_price'],
                    'sellable' => $item['sellable'] ?? false,
                ],
            ])
            ->toArray();

        DB::transaction(function () use ($data, $syncData) {
            $this->itemGroup->update([
                'name' => $data['name'],
                'coc_type' => $data['cocType'],
                'sellable' => $this->sellable,
            ]);

            $this->itemGroup->items()->sync($syncData);
        });

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.itemsmenu.item_group_updated'));
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
        return view('livewire.admin.itemsmenu.edit-item-group');
    }
}
