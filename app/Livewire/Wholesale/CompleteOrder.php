<?php

namespace App\Livewire\Wholesale;

use App\Models\CompanyNotification;
use App\Models\CompanyOrder;
use App\Models\WholesaleOrder;
use App\Models\Wholesaler;
use Livewire\Component;

class CompleteOrder extends Component
{
    public $wholesaler;
    public $order;
    
    public $orderItems;
    public $players;
    
    public $customerUuid;

    public $deleteOrderModal = null;
    public $undoCollectModal = null;

    public function mount($wholesalerId, $orderId) {
        $this->wholesaler = Wholesaler::where('id', $wholesalerId)->firstOrFail();
        $this->order = WholesaleOrder::where('id', $orderId)->firstOrFail();

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
        
        $this->players = collect([$owner])
            ->merge($managers->pluck('player'))
            ->map(fn ($player) => [
                'uuid' => $player->uuid,
                'username' => $player->username,
            ]);
    }

    public function completeOrder() {
        $data = $this->validate([
            'customerUuid' => 'required|exists:players,uuid',
        ]);

        $this->order->completed = true;
        $this->order->completed_by = auth()->user()->player->uuid;
        $this->order->customer_uuid = $this->customerUuid;
        $this->order->save();

        CompanyOrder::where('order_id', $this->order->id)->update(['status' => 'completed']);

        CompanyNotification::create([
            'company_id' => $this->order->company_id,
            'order_id' => $this->order->id,
            'type' => 'order_completed',
            'level' => 'info',
            'message' => __('wholesale.notifications.order_completed'),
        ]);

        return redirect()->route('wholesale.order-overview', ['wholesalerId' => $this->wholesaler->id])->success(__('wholesale.toasts.order_completed'));
    }

    public function removeOrder() {
        $this->deleteOrderModal = true;
    }

    public function destroyOrder() {
        $this->order->delete();

        return redirect()->route('wholesale.order-overview', ['wholesalerId' => $this->wholesaler->id])->success(__('wholesale.toasts.order_deleted'));
    }

    public function undoCollect() {
        $this->undoCollectModal = true;
    }

    public function undoCollectOrder() {
        $this->order->collected = false;
        $this->order->collected_by = null;
        $this->order->save();

        CompanyOrder::where('order_id', $this->order->id)->update(['status' => 'pending']);

        CompanyNotification::where('order_id', $this->order->id)
            ->where('type', 'order_collected')
            ->delete();

        return redirect()->route('wholesale.order-overview', ['wholesalerId' => $this->wholesaler->id])->success(__('wholesale.toasts.collect_order_undone'));
    }

    public function render()
    {
        return view('livewire.wholesale.complete-order');
    }
}
