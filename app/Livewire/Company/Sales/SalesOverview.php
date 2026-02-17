<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use App\Models\CompanyItem;
use Livewire\Component;

class SalesOverview extends Component
{
    public $company;

    public $currentYear;
    public $currentWeek;

    public $products;
    public $sales = [];
    public $total = 0;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->with(['country', 'sales', 'sales.items'])->first();

        $this->currentYear = now()->year;
        $this->currentWeek = now()->weekOfYear;

        $this->products = CompanyItem::where('company_id', $this->company->id)->with(['stock', 'item'])->get();
        foreach ($this->products as $index => $product) {
            $this->sales[$index] = ['item' => $product->item->name, 'amount' => 0, 'max_amount' => 999, 'price' => 0];
        }
    }

    public function increment($index, $amount = 1) {
        $this->sales[$index]['amount'] += $amount;
        if ($this->sales[$index]['amount'] < 0) {
            $this->sales[$index]['amount'] = 0;
        }
        $this->calculatePrice($index);
    }

    public function decrement($index, $amount = 1) {
        $this->sales[$index]['amount'] -= $amount;
        if ($this->sales[$index]['amount'] < 0) {
            $this->sales[$index]['amount'] = 0;
        }
        $this->calculatePrice($index);
    }

    public function calculatePrice($index) {
        $this->sales[$index]['price'] = $this->sales[$index]['amount'] * $this->products[$index]->price;
        $this->calculateTotal();
    }

    public function calculateTotal() {
        $this->total = array_sum(array_column($this->sales, 'price'));
    }

    public function render()
    {
        return view('livewire.company.sales.sales-overview');
    }
}
