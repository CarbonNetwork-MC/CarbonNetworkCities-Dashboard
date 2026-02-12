<?php

namespace App\Livewire\Wholesale;

use App\Models\Player;
use Livewire\Component;
use App\Models\WholesaleOrder;

class CompleteOrder extends Component
{
    public $order;
    public $orderItems;
    
    public $customerUuid;
    public $customer;
    public $players;

    public function mount($orderId) {
        $this->order = WholesaleOrder::findOrFail($orderId);
        $this->orderItems = $this->order->items()
            ->with(['item:id,name', 'item.wholesaleItem'])
            ->get(['id', 'item_id', 'amount'])
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->item->name,
                'item_id' => $item->item_id,
                'amount' => $item->amount,
                'total' => $item->amount * ($item->wholesaleItem ? $item->wholesaleItem->price : 0),
            ])
            ->toArray();

        $owner = $this->order->company->owner;
        $managers = $this->order->company->employees->where('role', 'manager');
        $managers->load('player');
        dd($owner, $managers);
    }

    public function completeOrder() {
        $this->order->completed = true;
        $this->order->completed_by = auth()->user()->player->uuid;
        $this->order->save();

        return redirect()->route('wholesale.order-overview')->success(__('wholesale.toasts.order_completed'));
    }

    public function render()
    {
        return view('livewire.wholesale.complete-order');
    }
}
