<?php

namespace App\Livewire\Admin\ItemsMenu;

use Livewire\Component;
use App\Models\ItemCategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class EditCategory extends Component
{
    use WithInvalidation;

    public $category;
    public $name;
    public $iconMaterial;

    public function mount($id) {
        $this->category = ItemCategory::findOrFail($id);
        $this->name = $this->category->name;
        $this->iconMaterial = $this->category->icon_material;
    }

    public function updateCategory() {
        if (!$this->category) return;

        $category = $this->category;

        $data = $this->validate([
            'name' => [
                'required', 'string', 'max:50',
                Rule::unique('item_categories', 'name')
                    ->ignore($this->category?->id, 'id'),
            ],
            'iconMaterial' => ['required', 'string', 'max:50']
        ]);

        $categoryName = strtolower($data['name']);
        $material = strtoupper(str_replace(' ', '_', $data['iconMaterial']));

        $this->category->name = $categoryName;
        $this->category->icon_material = $material;
        $this->category->save();

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/items");

        $requestId = $response->json('requestId');
            
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->category->name = $category['name'];
            $this->category->icon_material = $category['icon_material'];
            $this->category->save();
            return redirect()->route('admin.itemsmenu.render')->error(__('admin.toast.itemsmenu.reload_items_api_error'));
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.itemsmenu.category_updated'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.edit-category');
    }
}
