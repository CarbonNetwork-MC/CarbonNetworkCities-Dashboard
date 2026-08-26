<?php

namespace App\Livewire\Wholesale;

use App\Models\WholesaleOrder;
use App\Models\Wholesaler;
use Livewire\Component;

class OrderOverview extends Component
{
    public $wholesaler;

    public $searchOrders = '';
    public $searchCollectedOrders = '';
    public $searchCompletedOrders = '';

    public $ordersPerPage = 10;
    public $collectedOrdersPerPage = 5;
    public $completedOrdersPerPage = 5;

    public function mount($wholesalerId) {
        $this->wholesaler = Wholesaler::where('id', $wholesalerId)->firstOrFail();
    }

    public function selectOrder($orderId) {
        $order = WholesaleOrder::findOrFail($orderId);
        if (!$order->collected) {
            return redirect()->route('wholesale.collect-order', ['wholesalerId' => $this->wholesaler->id, 'orderId' => $orderId]);
        } else {
            return redirect()->route('wholesale.complete-order', ['wholesalerId' => $this->wholesaler->id, 'orderId' => $orderId]);
        }
    }

    public function render()
    {
        $orders = WholesaleOrder::with(['company:id,name'])
                ->where('wholesaler_id', $this->wholesaler->id)
                ->where('collected', 0)
                ->where('completed', 0)
                ->where(function ($query) {
                    $query
                        ->whereHas('company', function ($q) {
                            $q->where('name', 'like', '%' . $this->searchOrders . '%');
                        });
                })
                ->paginate($this->ordersPerPage, pageName: 'ordersPage');

        $collectedOrders = WholesaleOrder::with(['company:id,name', 'collectedBy:uuid,username'])
                ->where('wholesaler_id', $this->wholesaler->id)
                ->where('collected', 1)
                ->where('completed', 0)
                ->where(function ($query) {
                    $query
                        ->whereHas('company', function ($q) {
                            $q->where('name', 'like', '%' . $this->searchCollectedOrders . '%');
                        })
                        ->orWhereHas('collectedBy', function ($q) {
                            $q->where('username', 'like', '%' . $this->searchCollectedOrders . '%')
                            ->orWhere('uuid', 'like', '%' . $this->searchCollectedOrders . '%');
                        });
                })
                ->paginate($this->collectedOrdersPerPage, pageName: 'collectedOrdersPage');

        $completedOrders = WholesaleOrder::with(['company:id,name', 'customer:uuid,username', 'collectedBy:uuid,username', 'completedBy:uuid,username'])
                ->where('wholesaler_id', $this->wholesaler->id)
                ->where('collected', 1)
                ->where('completed', 1)
                ->where(function ($query) {
                    $query
                        ->whereHas('company', function ($q) {
                            $q->where('name', 'like', '%' . $this->searchCompletedOrders . '%');
                        })
                        ->orWhereHas('customer', function ($q) {
                            $q->where('username', 'like', '%' . $this->searchCompletedOrders . '%')
                            ->orWhere('uuid', 'like', '%' . $this->searchCompletedOrders . '%');
                        })
                        ->orWhereHas('collectedBy', function ($q) {
                            $q->where('username', 'like', '%' . $this->searchCompletedOrders . '%')
                            ->orWhere('uuid', 'like', '%' . $this->searchCompletedOrders . '%');
                        })
                        ->orWhereHas('completedBy', function ($q) {
                            $q->where('username', 'like', '%' . $this->searchCompletedOrders . '%')
                            ->orWhere('uuid', 'like', '%' . $this->searchCompletedOrders . '%');
                        });
                })
                ->paginate($this->completedOrdersPerPage, pageName: 'completedOrdersPage');

        return view('livewire.wholesale.order-overview', [
            'orders' => $orders,
            'collectedOrders' => $collectedOrders,
            'completedOrders' => $completedOrders,
        ]);
    }
}
