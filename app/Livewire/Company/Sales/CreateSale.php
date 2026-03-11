<?php

namespace App\Livewire\Company\Sales;

use App\Models\Company;
use App\Models\CompanyItem;
use App\Models\CompanySale;
use App\Models\CompanySaleItem;
use App\Models\EmployeeSalaryUpdate;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateSale extends Component
{
    public $company;

    public $currentYear;
    public $currentWeek;

    public $products;

    public $customer;
    public $sales = [];
    public $total = 0;

    public $stacks = 0;
    public $items = 0;
    public $calculatorTotal = 0;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->with(['country'])->first();

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

    public function createSale() {
        $data = $this->validate([
            'customer' => ['required', 'string', 'max:16', 'exists:players,username'],
            'sales' => ['required', 'array'],
            'sales.*.item' => ['required', 'string'],
            'sales.*.amount' => ['required', 'integer', 'min:0'],
            'sales.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        $customer = Player::where('username', $data['customer'])->first();
        
        $sale = CompanySale::create([
            'company_id' => $this->company->id,
            'year' => $this->currentYear,
            'week' => $this->currentWeek,
            'quantity' => array_sum(array_column($data['sales'], 'amount')),
            'total_revenue' => $this->total,
            'customer_uuid' => $customer->uuid,
            'employee_uuid' => Auth::user()->player->uuid,
        ]);

        foreach ($data['sales'] as $saleData) {
            $item = CompanyItem::where('company_id', $this->company->id)->whereHas('item', function($query) use ($saleData) {
                $query->where('name', $saleData['item']);
            })->first();
            
            if ($item) {
                if ($saleData['amount'] <= 0) continue;

                CompanySaleItem::create([
                    'company_sale_id' => $sale->id,
                    'item_id' => $item->id,
                    'quantity' => $saleData['amount'],
                    'price' => $saleData['price'],
                ]);

                // Reduce stock
                $stock = $item->stock()->first();
                if ($stock) {
                    $stock->quantity -= $saleData['amount'];
                    $stock->save();
                }
            }
        }

        $player = Auth::user()->player;

        // Calculate salary for the employee based on the sale. If the seller is an employee, use their specific salary percentage, if the seller is the owner or doesn't have a specific percentage, use the company's default salary percentage.
        $percentage = $this->company->employees()->where('player_uuid', $player->uuid)->first()->salary_percentage 
            ?? $this->company->settings->default_salary_percentage;
        $amount = round(($this->total * $percentage) / 100, 2);

        $existingSalary = $this->company->salaries()->where('player_uuid', $player->uuid)->where('year', $this->currentYear)->where('week', $this->currentWeek)->first();
        if (!$existingSalary) {
            $this->company->salaries()->create([
                'company_id' => $this->company->id,
                'player_uuid' => $player->uuid,
                'year' => $this->currentYear,
                'week' => $this->currentWeek,
                'amount' => $amount,
            ]);
        } else {
            $existingSalary->amount += $amount;
            $existingSalary->save();
        }

        EmployeeSalaryUpdate::create([
            'player_uuid' => $player->uuid,
            'sale_id' => $sale->id,
            'amount' => $amount,
        ]);

        return redirect()->route('company.sales.render', ['companyId' => $this->company->id])->success(__('company.toasts.sale_created'));
    }

    public function calculatePrice($index) {
        $this->sales[$index]['price'] = $this->sales[$index]['amount'] * $this->products[$index]->price;
        $this->calculateTotal();
    }

    public function calculateTotal() {
        $this->total = array_sum(array_column($this->sales, 'price'));
    }

    public function calculateToTotal() {
        $this->calculatorTotal = ($this->stacks * 64) + $this->items;
    }

    public function calculateFromTotal() {
        $this->stacks = floor($this->calculatorTotal / 64);
        $this->items = $this->calculatorTotal % 64;
    }

    public function resetCalculator() {
        $this->reset(['stacks', 'items', 'calculatorTotal']);
    }

    public function render()
    {
        return view('livewire.company.sales.create-sale');
    }
}
