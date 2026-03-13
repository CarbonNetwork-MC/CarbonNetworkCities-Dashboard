<?php

namespace App\Livewire\Admin\Wholesale;

use App\Models\Item;
use Livewire\Component;
use App\Models\WholesaleItem;

class EditItem extends Component
{
    public $item;

    public $itemId;
    public $price;
    public $maxAmount;

    public $allItems;

    public function mount($id) {
        $this->item = WholesaleItem::find($id);

        $this->itemId = $this->item->item_id;
        $this->price = $this->item->price;
        $this->maxAmount = $this->item->max_amount;

        $this->allItems = Item::whereNotIn('id', function ($query) {
            $query->select('item_id')
                ->from('wholesale_items')
                ->where('item_id', '!=', $this->item->item_id);
        })->get(['id', 'name']);
    }

    public function updateItem() {
        $data = $this->validate([
            'itemId' => ['required', 'integer', 'exists:items,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'maxAmount' => ['required', 'integer', 'min:1'],
        ]);

        $this->item->update([
            'item_id' => $data['itemId'],
            'price' => $data['price'],
            'max_amount' => $data['maxAmount'],
        ]);

        return redirect()->route('admin.wholesale-items.render')->success(__('admin.toasts.wholesale_items.updated'));
    }

    public function render()
    {
        return view('livewire.admin.wholesale.edit-item');
    }
}
