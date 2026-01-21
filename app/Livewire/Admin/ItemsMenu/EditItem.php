<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemCategory;
use Masmerise\Toaster\Toaster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Services\PluginAPI\InvalidationService;

class EditItem extends Component
{
    public $item;
    public $internalId;
    public $name;
    public $categoryId;
    public $category;
    public $material;
    public $categories;

    public function mount($id) {
        $this->item = Item::findOrFail($id);
        $this->internalId = $this->item->internal_id;
        $this->name = $this->item->name;
        $this->categoryId = $this->item->category_id;
        $this->category = ItemCategory::findOrFail($this->item->category_id);
        $this->material = $this->item->material;
        $this->categories = ItemCategory::all();
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
            'material' => ['required', 'string', 'max:50'],
        ]);

        $this->item->internal_id = $data['internalId'];
        $this->item->name = $data['name'];
        $this->item->category_id = $data['categoryId'];
        $this->item->material = strtoupper(str_replace(' ', '_', $data['material']));
        $this->item->save();

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/items");

        $requestId = $response->json('requestId');

        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->item->internal_id = $item['internalId'];
            $this->item->name = $item['name'];
            $this->item->category_id = $item['categoryId'];
            $this->item->material = $item['material'];
            $this->item->save();
            return Toaster::error(__('admin.toast.reload_items_api_error'));
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.item_updated'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.edit-item');
    }

    private function waitForInvalidationResult(string $requestId): bool {
        return app(InvalidationService::class)->waitForInvalidationResult($requestId);
    }
}
