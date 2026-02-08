<?php

namespace App\Livewire\Admin\Wholesale;

use App\Models\WholesaleItem;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    public $search = '';
    public $itemsPerPage = 25;

    public $selectedItem = null;
    public $deleteItemModal = false;

    public function removeItem($id) {
        $this->selectedItem = WholesaleItem::where('id', $id)->with('item')->first();
        $this->deleteItemModal = true;
    }

    public function destroyItem() {
        if (!$this->selectedItem) return;

        $this->selectedItem->delete();
        
        $this->reset([
            'selectedItem',
            'deleteItemModal',
        ]);

        Toaster::success(__('admin.toasts.wholesale_items.deleted'));
    }

    public function render()
    {
        return view('livewire.admin.wholesale.overview', [
            'items' => WholesaleItem::with('item')
                ->whereHas('item', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->paginate($this->itemsPerPage),
        ]);
    }
}
