<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;

class UpdateStock extends Component
{
    public $company;
    public $stockUpdates = [];

    public function mount($companyId) {
        $this->company = Company::with(['items', 'stock'])->findOrFail($companyId);
        foreach ($this->company->items as $item) {
            $this->stockUpdates[$item->id] = $item->stock->quantity ?? 0;
        }
    }

    public function saveStock() {
        foreach ($this->stockUpdates as $itemId => $quantity) {
            $stock = $this->company->stock()->where('item_id', $itemId)->first();
            if ($stock) {
                $stock->update(['quantity' => $quantity]);
            }
        }

        return redirect()->route('company.stock.render', ['companyId' => $this->company->id])->success(__('company.toasts.stock_updated'));
    }

    public function render()
    {
        return view('livewire.company.update-stock');
    }
}
