<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemCategory;
use App\Services\PluginAPI\ApiService;
use Masmerise\Toaster\Toaster;

class NewItem extends Component
{
    public $internalId = '';
    public $name = '';
    public $categoryId = null;
    public $iconMaterial = '';
    public $displayName = '';
    public array $lore = [''];
    public $shelfLife = null;
    public $expiredPrefix = '';

    public $isFood = false;

    public function updated($key, $value) {
        if ($key === 'categoryId') {
            $category = ItemCategory::find($value);
            $this->isFood = $category && $category->name === 'food';
        }
    }

    public function createItem(ApiService $apiService) {
        $data = $this->validate([
            'internalId' => ['required', 'string', 'max:100', 'unique:items,internal_id'],
            'name' => ['required', 'string', 'max:100', 'unique:items,name'],
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

        $newItem = Item::create([
            'internal_id' => $data['internalId'],
            'name' => $data['name'],
            'category_id' => $data['categoryId'],
            'material' => $material,
            'user_uuid' => auth()->user()->uuid,
            'data' => $itemData,
        ]);
        
        [$status, $success] = $apiService->post("api/reload/items");

        if (!$success) {
            $newItem->delete();
            return Toaster::error(__('admin.toasts.itemsmenu.reload_items_api_error'));
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toasts.itemsmenu.item_created'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.new-item', [
            'categories' => ItemCategory::get(['id', 'name']),
            'expiredPrefixes' => config('items.expired_prefixes'),
        ]);
    }

    public function addLoreLine() {
        $this->lore[] = '';
    }

    public function removeLoreLine($index) {
        unset($this->lore[$index]);
        $this->lore = array_values($this->lore);
    }
}
