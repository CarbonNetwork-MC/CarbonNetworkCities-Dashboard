<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemCategory;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class NewItem extends Component
{
    use WithInvalidation;

    public $internalId = '';
    public $name = '';
    public $categoryId = null;
    public $iconMaterial = '';

    public function createItem() {
        $data = $this->validate([
            'internalId' => ['required', 'string', 'max:100', 'unique:items,internal_id'],
            'name' => ['required', 'string', 'max:100', 'unique:items,name'],
            'categoryId' => ['required', 'integer', 'exists:item_categories,id'],
            'iconMaterial' => ['required', 'string', 'max:50'],
        ]);

        $material = strtoupper($data['iconMaterial']);

        $newItem = Item::create([
            'internal_id' => $data['internalId'],
            'name' => $data['name'],
            'category_id' => $data['categoryId'],
            'material' => $material,
            'user_uuid' => auth()->user()->uuid,
        ]);

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/items");

        $requestId = $response->json('requestId');

        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $newItem->delete();
            return Toaster::error(__('admin.toast.reload_items_api_error'));
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.item_created'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.new-item', [
            'categories' => ItemCategory::all(),
        ]);
    }
}
