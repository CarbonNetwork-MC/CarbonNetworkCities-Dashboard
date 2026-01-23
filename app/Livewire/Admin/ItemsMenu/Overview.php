<?php

namespace App\Livewire\Admin\ItemsMenu;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemCategory;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class Overview extends Component
{
    use WithPagination;
    use WithInvalidation;

    public $searchCategory = '';
    public $searchItem = '';

    public $categoryId = null;
    public $remainingCategories = [];


    public $categoriesPerPage = 5;
    public $itemsPerPage = 10;

    public $deleteCategoryModal = false;
    public $deleteItemModal = false;

    public $selectedCategory = null;
    public $selectedItem = null;

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
        $this->remainingCategories = ItemCategory::where('id', '!=', $id)->get();
        $this->deleteCategoryModal = true;
    }

    public function destroyCategory() {
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

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/items");

        $requestId = $response->json('requestId');

        $success = $this->waitForInvalidationResult($requestId);
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

            return Toaster::error(__('admin.toast.reload_items_api_error'));
        }

        $this->reset([
            'selectedCategory',
            'deleteCategoryModal',
        ]);

        Toaster::success(__('admin.toast.category_deleted'));
    }

    // ? Item Methods
    public function removeItem($id) {
        $this->selectedItem = Item::find($id);
        $this->deleteItemModal = true;
    }

    public function destroyItem() {
        if (!$this->selectedItem) return;

        $selectedItem = $this->selectedItem;

        $this->selectedItem->delete();

        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/reload/items");

        $requestId = $response->json('requestId');

        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            Item::create($selectedItem->toArray());
            return Toaster::error(__('admin.toast.reload_items_api_error'));
        }

        $this->reset([
            'selectedItem',
            'deleteItemModal',
        ]);

        Toaster::success(__('admin.toast.item_deleted'));
    }

    public function render()
    {
        return view('livewire.admin.itemsmenu.overview', [
            'categories' => ItemCategory::where('name', 'like', '%' . $this->searchCategory . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($this->categoriesPerPage, pageName: 'categoriesPage'),
            'items' => Item::where('name', 'like', '%' . $this->searchItem . '%')
                ->orderBy('created_at', 'desc')
                ->paginate($this->itemsPerPage, pageName: 'itemsPage'),
        ]);
    }
}
