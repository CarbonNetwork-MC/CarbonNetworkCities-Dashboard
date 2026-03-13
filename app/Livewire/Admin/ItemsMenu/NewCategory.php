<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\ItemCategory;
use App\Services\RedisService;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class NewCategory extends Component
{
    public $name = '';
    public $iconMaterial = '';

    public function createCategory(RedisService $redisService) {
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

        $success = $redisService->invalidate('RELOAD_ITEMS', 'NULL');
        if (!$success) {
            $newCategory->delete();
            Toaster::error(__('admin.toast.itemsmenu.reload_items_api_error'));
            return;
        }

        return redirect()->route('admin.itemsmenu.render')->success(__('admin.toast.itemsmenu.category_created'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.new-category');
    }
}
