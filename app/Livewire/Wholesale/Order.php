<?php

namespace App\Livewire\Wholesale;

use App\Models\WholesaleOrder;
use Livewire\Component;

class Order extends Component
{
    public $order;
    public $orderItems;

    public function mount($orderId) {
        $this->order = WholesaleOrder::findOrFail($orderId);

        dd($this->order->items()->with(['wholesaleItem'])->get());

        // $this->orderItems = $this->order->items()
        //     ->with(['item:id,name', 'item.wholesaleItem'])
        //     ->get(['id', 'item_id'])
        //     ->map(fn ($item) => [
        //         'id' => $item->id,
        //         'name' => $item->item->name,
        //         'item_id' => $item->item_id,
        //         'max_amount' => $item->wholesaleItem ? $item->wholesaleItem->max_amount : 192,
        //         'amount' => 0,
        //         'price' => $item->wholesaleItem ? $item->wholesaleItem->price : 0,
        //         'total' => 0,
        //     ])
        //     ->toArray();
    }

    public function render()
    {
        return view('livewire.wholesale.order');
    }
}
