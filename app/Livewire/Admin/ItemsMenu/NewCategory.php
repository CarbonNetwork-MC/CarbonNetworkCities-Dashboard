<?php

namespace App\Livewire\Admin\ItemsMenu;

use Livewire\Component;
use App\Models\ItemCategory;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
use App\Services\PluginAPI\InvalidationService;

class NewCategory extends Component
{
    public $name = '';
    public $iconMaterial = '';

    public function createCategory() {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:50', 'unique:item_categories,name'],
            'iconMaterial' => ['required', 'string', 'max:50']
        ]);

        $categoryName = strtolower($data['name']);
        $material = strtoupper(str_replace(' ', '_', $data['iconMaterial']));

        $newCategory = ItemCategory::create([
            'name' => $categoryName,
            'icon_material' => $material,
            'user_uuid' => auth()->user()->uuid,
        ]);

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/items");

        $requestId = $response->json('requestId');
            
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $newCategory->delete();
            return Toaster::error(__('admin.toast.reload_items_api_error'));
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.category_created'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.new-category');
    }

    private function waitForInvalidationResult(string $requestId): bool {
        return app(InvalidationService::class)->waitForInvalidationResult($requestId);
    }
}
