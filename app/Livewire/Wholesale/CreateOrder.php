<?php

namespace App\Livewire\Wholesale;

use App\Models\Company;
use App\Models\CompanyOrder;
use App\Models\WholesaleOrder;
use App\Models\Wholesaler;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class CreateOrder extends Component
{
    public $company;
    public $wholesaler;

    public $orderItems;
    public $total = 0;

    public function mount($wholesalerId, $companyId) {
        $this->company = Company::where('id', $companyId)->firstOrFail();
        $this->wholesaler = Wholesaler::where('id', $wholesalerId)->firstOrFail();

        // Get all items that the company has, along with their wholesale info, and filter out those that aren't sellable. Then map them to the format needed for the order.
        $this->orderItems = $this->company->items()
            ->with([
                'item:id,name',
                'wholesaleItem' => function ($query) use ($wholesalerId) {
                    $query->where('wholesaler_id', $wholesalerId)
                        ->where('sellable', 1);
                }
            ])
            ->whereHas('wholesaleItem', function ($query) use ($wholesalerId) {
                $query->where('wholesaler_id', $wholesalerId)
                    ->where('sellable', 1);
            })
            ->get(['id', 'item_id'])
            ->map(function ($item) {
                $wholesale = $item->wholesaleItem;

                return [
                    'id' => $item->id,
                    'name' => $item->item->name,
                    'item_id' => $item->item_id,
                    'max_amount' => $wholesale?->max_amount ?? 192,
                    'amount' => 0,
                    'price' => $wholesale?->price ?? 0,
                    'total' => 0,
                ];
            })
            ->toArray();

        if (count($this->company->items) === 0) {
            return redirect()->route('wholesale.start', ['step' => 1])->error(__('wholesale.toasts.no_items_company'));
        }
        if (count($this->orderItems) === 0) {
            return redirect()->route('wholesale.start', ['step' => 1])->error(__('wholesale.toasts.no_items_wholesaler'));
        }
    }

    public function createOrder() {
        if ($this->total <= 0) {
            Toaster::error(__('wholesale.toasts.no_items_in_order'));
            return;
        }

        foreach ($this->orderItems as $index => $item) {
            if ($item['amount'] > $item['max_amount']) {
                $this->orderItems[$index]['amount'] = $item['max_amount'];
                Toaster::error(__('wholesale.toasts.max_amount_exceeded', ['item' => $item['name'], 'max' => $item['max_amount']]));
                return;
            }
        }
        
        $order = WholesaleOrder::create([
            'wholesaler_id' => $this->wholesaler->id,
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

        CompanyOrder::create([
            'company_id' => $this->company->id,
            'order_id' => $order->id,
        ]);

        return redirect()->route('wholesale.start', ['step' => 1])->success(__('wholesale.toasts.order_created'));
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
        return view('livewire.wholesale.create-order');
    }
}
