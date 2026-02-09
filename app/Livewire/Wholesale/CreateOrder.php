<?php

namespace App\Livewire\Wholesale;

use App\Models\Company;
use App\Models\WholesaleOrder;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class CreateOrder extends Component
{
    public $company;

    public $orderItems;
    public $total = 0;

    public function mount($companyId) {
        $this->company = Company::findOrFail($companyId);
        
        $this->orderItems = $this->company->items()
            ->with(['item:id,name', 'wholesaleItem'])
            ->whereHas('wholesaleItem', function ($query) {
                $query->where('sellable', 1);
            })
            ->get(['id', 'item_id'])
            ->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->item->name,
                'item_id' => $item->item_id,
                'max_amount' => $item->wholesaleItem ? $item->wholesaleItem->max_amount : 192,
                'amount' => 0,
                'price' => $item->wholesaleItem ? $item->wholesaleItem->price : 0,
                'total' => 0,
            ])
            ->toArray();

        if (count($this->orderItems) === 0) {
            return redirect()->route('wholesale.choose-company')->error(__('wholesale.toasts.no_items'));
        }
    }

    public function createOrder() {
        if ($this->total <= 0) {
            Toaster::error(__('wholesale.toasts.no_items_in_order'));
        }

        foreach ($this->orderItems as $index => $item) {
            if ($item['amount'] > $item['max_amount']) {
                $this->orderItems[$index]['amount'] = $item['max_amount'];
                Toaster::error('wholesale.toasts.max_amount_exceeded', ['item' => $item['name'], 'max' => $item['max_amount']]);
                return;
            }
        }
        
        $order = WholesaleOrder::create([
            'company_id' => $this->company->id,
            'total' => $this->total,
        ]);

        $order->items()->createMany(
            collect($this->orderItems)
                ->filter(fn ($item) => $item['amount'] > 0)
                ->map(fn ($item) => [
                    'item_id' => $item['item_id'],
                    'amount' => $item['amount'],
                    'price' => $item['price'],
                ])
                ->toArray()
        );

        return redirect()->route('wholesale.choose-company')->success(__('wholesale.toasts.order_created'));
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
        return view('livewire.wholesale.create-order', [
            'items' => $this->company->items()
                ->with(['item:id,name', 'wholesaleItem'])
                ->whereHas('wholesaleItem', function ($query) {
                    $query->where('sellable', 1);
                })
                ->get(['id', 'item_id'])
        ]);
    }
}
