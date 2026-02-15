<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyOrder;
use Livewire\Component;
use Livewire\WithPagination;

class WholesaleOrders extends Component
{
    use WithPagination;

    public $company;

    public $orderPerPage = 10;

    public function mount($companyId) {
        $this->company = Company::find($companyId);
    }

    public function render()
    {
        $orders = CompanyOrder::where('company_id', $this->company->id)
            ->with('order', 'order.items')
            ->orderBy('completed', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->orderPerPage);

        return view('livewire.company.wholesale-orders', [
            'orders' => $orders,
        ]);
    }
}
