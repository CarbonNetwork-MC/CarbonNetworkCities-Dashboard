<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Services\RedisService;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class EditItem extends Component
{
    public $item;
    public $internalId;
    public $name;
    public $categoryId;
    public $iconMaterial;
    public $displayName;
    public array $lore;
    public $shelfLife;
    public $expiredPrefix;

    public $isFood;

    public function mount($id) {
        $this->item = Item::findOrFail($id);
        $this->internalId = $this->item->internal_id;
        $this->name = $this->item->name;
        $this->categoryId = $this->item->category_id;
        $this->iconMaterial = $this->item->material;
        $this->displayName = $this->item->data['display_name'] ?? '';
        $this->lore = $this->item->data['lore'] ?? [''];
        $this->shelfLife = $this->item->data['shelf_life'] ?? null;
        $this->expiredPrefix = $this->item->data['expired_prefix'] ?? null;
        $this->isFood = $this->item->category->name === 'food';
    }

    public function updated($key, $value) {
        if ($key === 'categoryId') {
            $category = ItemCategory::find($value);
            $this->isFood = $category && $category->name === 'food';
        }
    }

    public function updateItem(RedisService $redisService) {
        if (!$this->item) return;

        $item = $this->item;
        $this->expiredPrefix = str_replace('<green>', '', $this->expiredPrefix);
    
        $data = $this->validate([
            'internalId' => [
                'required', 'string', 'max:100', 
                Rule::unique('items', 'internal_id')
                    ->ignore($this->item?->id, 'id'),
            ],
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('items', 'name')
                    ->ignore($this->item?->id, 'id'),
            ],
            'categoryId' => ['required', 'integer', 'exists:item_categories,id'],
            'iconMaterial' => ['required', 'string', 'max:50'],
            'displayName' => ['required', 'string', 'max:50'],
            'lore' => ['nullable', 'array'],
            'lore.*' => ['nullable', 'string', 'max:255'],
            'shelfLife' => ['nullable', 'integer', 'min:0'],
            'expiredPrefix' => ['nullable', 'string', 'in:' . implode(',', config('items.expired_prefixes'))],
        ]);

        $material = strtoupper(str_replace(' ', '_', $data['iconMaterial']));

        $itemData = [
            'lore' => $this->lore,
            'display_name' => $this->displayName,
        ];

        if ($this->isFood) {
            $itemData['shelf_life'] = $this->shelfLife;
            $itemData['expired_prefix'] = "<green>" . $this->expiredPrefix;
        }

        $this->item->internal_id = $data['internalId'];
        $this->item->name = $data['name'];
        $this->item->category_id = $data['categoryId'];
        $this->item->material = $material;
        $this->item->data = $itemData;
        $this->item->save();
        
        $success = $redisService->invalidate('RELOAD_ITEMS', 'NULL');
        if (!$success) {
            $this->item->internal_id = $item['internal_id'];
            $this->item->name = $item['name'];
            $this->item->category_id = $item['category_id'];
            $this->item->material = $item['material'];
            $this->item->data = $item['data'];
            $this->item->save();
            Toaster::error(__('admin.toast.itemsmenu.reload_items_api_error'));
            return;
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toasts.itemsmenu.item_updated'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.edit-item', [
            'categories' => ItemCategory::get(['id', 'name']),
            'expiredPrefixes' => config('items.expired_prefixes'),
        ]);
    }

    public function addLoreLine()
    {
        $this->lore[] = '';
    }

    public function removeLoreLine($index)
    {
        unset($this->lore[$index]);
        $this->lore = array_values($this->lore);
    }
}
