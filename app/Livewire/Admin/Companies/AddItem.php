<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Item;
use App\Models\Company;
use Livewire\Component;

class AddItem extends Component
{
    public $company;

    public $items;

    public $selectedItem;
    public $price;
    public $basePrice;
    public $sellable = false;

    public function mount($id) {
        $this->company = Company::findOrFail($id);

        $companyItems = $this->company->items()->pluck('item_id')->toArray();
        $this->items = Item::whereNotIn('id', $companyItems)->get(['id', 'internal_id']);
    }

    public function addItem() {
        $data = $this->validate([
            'selectedItem' => ['required', 'exists:items,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'basePrice' => ['required', 'numeric', 'min:0']
        ]);

        $this->company->items()->create([
            'company_id' => $this->company->id,
            'item_id' => $data['selectedItem'],
            'price' => $data['price'],
            'base_price' => $this->basePrice,
            'sellable' => $this->sellable
        ]);

        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.companies.item_added'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-item');
    }
}
