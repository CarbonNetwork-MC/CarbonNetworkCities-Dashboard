<?php

namespace App\Livewire\Wholesale;

use App\Models\WholesaleOrder;
use Livewire\Component;

class OrderOverview extends Component
{
    public $searchOrders = '';
    public $searchCollectedOrders = '';
    public $searchCompletedOrders = '';

    public $ordersPerPage = 10;
    public $collectedOrdersPerPage = 5;
    public $completedOrdersPerPage = 5;

    public function selectOrder($orderId) {
        return redirect()->route('wholesale.order', ['orderId' => $orderId]);
        $order = WholesaleOrder::findOrFail($orderId);
        if (!$order->collected) {
            return redirect()->route('wholesale.collect-order', ['orderId' => $orderId]);
        }
    }

    public function render()
    {
        return view('livewire.wholesale.order-overview', [
            'orders' => WholesaleOrder::with(['company:id,name'])
                ->where('collected', 0)
                ->where('completed', 0)
                ->where(function ($query) {
                    $query
                        ->whereHas('company', function ($q) {
                            $q->where('name', 'like', '%' . $this->searchOrders . '%');
                        });
                })
                ->paginate($this->ordersPerPage, pageName: 'ordersPage'),
            'collectedOrders' => WholesaleOrder::with(['company:id,name', 'collectedBy:uuid,username'])
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
                ->paginate($this->collectedOrdersPerPage, pageName: 'collectedOrdersPage'),
            'completedOrders' => WholesaleOrder::with(['company:id,name', 'collectedBy:uuid,username', 'completedBy:uuid,username'])
                ->where('collected', 1)
                ->where('completed', 1)
                ->where(function ($query) {
                    $query
                        ->whereHas('company', function ($q) {
                            $q->where('name', 'like', '%' . $this->searchCompletedOrders . '%');
                        })
                        ->orWhereHas('completedBy', function ($q) {
                            $q->where('username', 'like', '%' . $this->searchCompletedOrders . '%')
                            ->orWhere('uuid', 'like', '%' . $this->searchCompletedOrders . '%');
                        });
                })
                ->paginate($this->completedOrdersPerPage, pageName: 'completedOrdersPage'),
        ]);
    }
}
