<?php

namespace App\Livewire\Wholesale;

use App\Models\CompanyNotification;
use App\Models\CompanyOrder;
use App\Models\WholesaleOrder;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class CollectOrder extends Component
{
    public $order;
    public $total;
    public $orderItems;

    public $editOrder = false;

    public function mount($orderId) {
        $this->order = WholesaleOrder::findOrFail($orderId);

        $this->orderItems = $this->order->items()
            ->with(['item:id,name', 'item.wholesaleItem'])
            ->get(['id', 'item_id', 'amount'])
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->item->name,
                'item_id' => $item->item_id,
                'max_amount' => $item->wholesaleItem ? $item->wholesaleItem->max_amount : 192,
                'amount' => $item->amount,
                'price' => $item->wholesaleItem ? $item->wholesaleItem->price : 0,
                'total' => $item->amount * ($item->wholesaleItem ? $item->wholesaleItem->price : 0),
            ])
            ->toArray();

        $this->calculateTotal();
    }

    public function updateOrder() {
        if ($this->total <= 0) {
            Toaster::error(__('wholesale.toasts.no_items_in_order'));
            return;
        }

        foreach ($this->orderItems as $index => $item) {        
            if ($item['amount'] > $item['max_amount']) {
                $this->orderItems[$index]['amount'] = $item['max_amount'];
                Toaster::error('wholesale.toasts.max_amount_exceeded', ['item' => $item['name'], 'max' => $item['max_amount']]);
                return;
            }
        }

        foreach ($this->orderItems as $item) {
            $orderItem = $this->order->items()->where('item_id', $item['item_id'])->first();
            if ($orderItem) {
                $orderItem->update(['amount' => $item['amount']]);
            }
        }

        $this->order->update(['total' => $this->total]);

        $this->editOrder = false;

        Toaster::success(__('wholesale.toasts.order_updated'));
    }

    public function collectOrder() {
        $this->order->collected = true;
        $this->order->collected_by = auth()->user()->player->uuid;
        $this->order->save();

        CompanyOrder::where('order_id', $this->order->id)->update(['status' => 'collected']);

        CompanyNotification::create([
            'company_id' => $this->order->company_id,
            'order_id' => $this->order->id,
            'type' => 'order_collected',
            'level' => 'info',
            'message' => __('wholesale.notifications.order_collected'),
        ]);

        return redirect()->route('wholesale.order-overview')->success(__('wholesale.toasts.order_collected'));
    }

    public function increment($index)
    {
        $this->orderItems[$index]['amount']++;
        $this->calculatePrice($index);
    }

    public function decrement($index)
    {
        if ($this->orderItems[$index]['amount'] > 0) {
            $this->orderItems[$index]['amount']--;
            $this->calculatePrice($index);
        }
    }

    public function calculatePrice($index) {
        $this->orderItems[$index]['total'] = $this->orderItems[$index]['price'] * (int) $this->orderItems[$index]['amount'];
        $this->calculateTotal();
    }

    private function calculateTotal() {
        $this->total = array_sum(array_column($this->orderItems, 'total'));
    }

    public function render()
    {
        return view('livewire.wholesale.collect-order');
    }
}
