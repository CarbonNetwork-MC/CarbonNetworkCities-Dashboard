<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\CompanyItem;
use App\Models\Item;
use Livewire\Component;

class EditItem extends Component
{
    public $company;
    public $item;

    public $items;

    public $selectedItem;
    public $price;
    public $basePrice;
    public $sellable = false;

    public function mount($companyId, $itemId) {
        $this->company = Company::findOrFail($companyId);
        $this->item = CompanyItem::findOrFail($itemId);

        $this->selectedItem = $this->item->item_id;
        $this->price = $this->item->price;
        $this->basePrice = $this->item->base_price;
        $this->sellable = (bool) $this->item->sellable;

        $this->items = Item::get(['id', 'internal_id']);
    }

    public function updateItem() {
        $data = $this->validate([
            'selectedItem' => ['required', 'exists:items,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'basePrice' => ['required', 'numeric', 'min:0']
        ]);

        $this->item->update([
            'item_id' => $data['selectedItem'],
            'price' => $data['price'],
            'base_price' => $data['basePrice'],
            'sellable' => $this->sellable
        ]);

        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toasts.companies.item_updated'));
    }

    public function render()
    {
        return view('livewire.admin.companies.edit-item');
    }
}
