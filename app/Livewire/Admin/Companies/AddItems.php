<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Item;
use App\Models\Company;
use App\Models\CompanyStock;
use App\Models\ItemGroup;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class AddItems extends Component
{
    public $company;

    public $allItems;
    public $itemGroups;

    public $selectedItem;
    public $price;
    public $basePrice;
    public $sellable = false;
    public $selectedItemGroupId;
    public $selectedItemGroup;

    public array $items = [];

    public $showAddItemGroupModal = false;

    public function mount($id) {
        $this->company = Company::findOrFail($id);

        $companyItems = $this->company->items()->pluck('item_id')->toArray();
        $this->allItems = Item::whereNotIn('id', $companyItems)->get(['id', 'internal_id']);
        $this->itemGroups = ItemGroup::with('items')->get(['id', 'name']);

        $this->items = [
            $this->emptyItem()
        ];
    }

    public function updated($key, $value) {
        if ($key === 'selectedItemGroupId') {
            $this->selectedItemGroup = $this->itemGroups->firstWhere('id', $value);
        }
    }

    public function addItems() {
        $data = $this->validate([
            'items.*.item_id' => ['required', 'distinct', 'exists:items,id'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.base_price' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($this->items as $item) {
            if (!$item['item_id']) {
                continue;
            }

            $item = $this->company->items()->create([
                'company_id' => $this->company->id,
                'item_id' => $item['item_id'],
                'price' => $item['price'] ?? 0,
                'base_price' => $item['base_price'] ?? 0,
                'sellable' => $item['sellable'],
            ]);

            $this->company->stock()->create([
                'company_id' => $this->company->id,
                'item_id' => $item->id,
                'quantity' => 0,
            ]);
        }

        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.companies.item_added'));
    }

    public function addItemGroup() {
        if (!$this->selectedItemGroup) {
            $this->showAddItemGroupModal = false;
            Toaster::error(__('admin.toast.companies.no_group_selected'));
            return;
        }

        // Check if the items array has any empty items and remove them
        foreach ($this->items as $item) {
            if (empty($item['item_id'])) {
                $this->removeItem(array_search($item, $this->items));
            }
        }

        foreach ($this->selectedItemGroup->items as $item) {
            // Avoid adding duplicate items
            $existingItemIds = collect($this->company->items)->pluck('item_id')->toArray();
            if (in_array($item->id, $existingItemIds)) {
                continue;
            }

            $this->items[] = [
                'item_id' => $item->id,
                'price' => $item->pivot->price,
                'base_price' => $item->pivot->base_price,
                'sellable' => (bool) $item->pivot->sellable,
            ];
            $this->items = array_values($this->items);
        }

        $this->showAddItemGroupModal = false;
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
        return view('livewire.admin.companies.add-items');
    }
}
