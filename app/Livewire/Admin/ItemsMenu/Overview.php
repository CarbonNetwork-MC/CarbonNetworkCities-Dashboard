<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemGroup;
use App\Services\RedisService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $searchCategory = '';
    public $searchItem = '';

    public $categoryId = null;
    public $remainingCategories = [];

    public $categoriesPerPage = 5;
    public $itemsPerPage = 10;
    public $itemGroupsPerPage = 10;

    public $deleteCategoryModal = false;
    public $deleteItemModal = false;
    public $deleteItemGroupModal = false;

    public $selectedCategory = null;
    public $selectedItem = null;
    public $selectedItemGroup = null;

    // ? Pagination Method
    public function updated($key, $value) {
        if ($key === 'searchCategory') {
            $this->resetPage('categoriesPage');
        }

        if ($key === 'searchItem') {
            $this->resetPage('itemsPage');
        }
    }

    // ? Category Methods
    public function removeCategory($id) {
        $this->selectedCategory = ItemCategory::find($id);
        $this->remainingCategories = ItemCategory::where('id', '!=', $id)->get(['id', 'name']);
        $this->deleteCategoryModal = true;
    }

    public function destroyCategory(RedisService $redisService) {
        if (!$this->selectedCategory) return;

        $selectedCategory = $this->selectedCategory;
        $oldItems = Item::where('category_id', $this->selectedCategory->id)->get();

        if ($this->categoryId) {
            Item::where('category_id', $this->selectedCategory->id)
                ->update(['category_id' => $this->categoryId]);
        } else {
            Item::where('category_id', $this->selectedCategory->id)
                ->delete();
        }

        $this->selectedCategory->delete();

        $success = $redisService->invalidate('RELOAD_ITEMS', 'NULL');
        if (!$success) {
            ItemCategory::create($selectedCategory->toArray());
            
            if ($this->categoryId) {
                foreach ($oldItems as $item) {
                    Item::where('id', $item->id)
                        ->update(['category_id' => $this->selectedCategory->id]);
                }
            } else {
                Item::insert($oldItems->toArray());
            }

            Toaster::error(__('admin.toast.itemsmenu.reload_items_api_error'));
            return;
        }

        $this->reset([
            'selectedCategory',
            'deleteCategoryModal',
        ]);

        Toaster::success(__('admin.toasts.itemsmenu.category_deleted'));
    }

    // ? Item Methods
    public function removeItem($id) {
        $this->selectedItem = Item::find($id);
        $this->deleteItemModal = true;
    }

    public function destroyItem(RedisService $redisService) {
        if (!$this->selectedItem) return;

        $selectedItem = $this->selectedItem;

        $this->selectedItem->delete();
        $success = $redisService->invalidate('RELOAD_ITEMS', 'NULL');

        if (!$success) {
            Item::create($selectedItem->toArray());
            Toaster::error(__('admin.toast.itemsmenu.reload_items_api_error'));
            return;
        }

        $this->reset([
            'selectedItem',
            'deleteItemModal',
        ]);

        Toaster::success(__('admin.toasts.itemsmenu.item_deleted'));
    }

    // ? Item Group Methods
    public function removeItemGroup($id) {
        $this->selectedItemGroup = ItemGroup::find($id);
        $this->deleteItemGroupModal = true;
    }

    public function destroyItemGroup() {
        if (!$this->selectedItemGroup) return;

        $this->selectedItemGroup->delete();

        $this->reset([
            'selectedItemGroup',
            'deleteItemGroupModal',
        ]);

        Toaster::success(__('admin.toasts.itemsmenu.item_group_deleted'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.overview', [
            'categories' => ItemCategory::where('name', 'like', '%' . $this->searchCategory . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($this->categoriesPerPage, pageName: 'categoriesPage'),
            'items' => Item::where('name', 'like', '%' . $this->searchItem . '%')
                ->orWhereHas('category', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchItem . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->itemsPerPage, pageName: 'itemsPage'),
            'itemGroups' => ItemGroup::where('coc_type', 'like', '%' . $this->searchItem . '%')
                ->with('cocType')
                ->orderBy('created_at', 'desc')
                ->paginate($this->itemGroupsPerPage, pageName: 'itemGroupsPage'),
        ]);
    }
}
