<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemCategory;
use Masmerise\Toaster\Toaster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class EditItem extends Component
{
    use WithInvalidation;

    public $item;
    public $internalId;
    public $name;
    public $categoryId;
    public $iconMaterial;
    public $displayName;
    public array $lore;
    public $shelfLife;
    public $expiredPrefix;

    public $isFood = false;

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
    }

    public function updated($key, $value) {
        if ($key === 'categoryId') {
            $category = ItemCategory::find($value);
            $this->isFood = $category && $category->name === 'food';
        }
    }

    public function updateItem() {
        if (!$this->item) return;

        $item = $this->item;
    
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

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/items");

        $requestId = $response->json('requestId');

        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->item->internal_id = $item['internal_id'];
            $this->item->name = $item['name'];
            $this->item->category_id = $item['category_id'];
            $this->item->material = $item['material'];
            $this->item->data = $item['data'];
            $this->item->save();
            return Toaster::error(__('admin.toast.itemsmenu.reload_items_api_error'));
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.itemsmenu.item_updated'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.edit-item', [
            'categories' => ItemCategory::all(),
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
