<?php

namespace App\Livewire\Admin\Wholesale;

use App\Models\Item;
use Livewire\Component;
use App\Models\WholesaleItem;

class NewItem extends Component
{
    public $itemId;
    public $price;
    public $maxAmount;

    public $allItems;

    public function mount() {
        $this->allItems = Item::whereNotIn('id', function ($query) {
            $query->select('item_id')->from('wholesale_items');
        })->get(['id', 'name']);
    }

    public function createItem() {
        $data = $this->validate([
            'itemId' => ['required', 'integer', 'exists:items,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'maxAmount' => ['required', 'integer', 'min:1'],
        ]);

        WholesaleItem::create([
            'item_id' => $data['itemId'],
            'price' => $data['price'],
            'max_amount' => $data['maxAmount'],
        ]);

        return redirect()->route('admin.wholesale-items.render')->success(__('admin.toasts.wholesale_items.created'));
    }

    public function render()
    {
        return view('livewire.admin.wholesale.new-item');
    }
}
