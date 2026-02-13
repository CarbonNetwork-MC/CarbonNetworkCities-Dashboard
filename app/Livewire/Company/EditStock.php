<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyStock;
use Livewire\Component;

class EditStock extends Component
{
    public $company;
    public $stockItem;

    public $itemName;
    public $preferredStockLevel;
    public $warningThreshold;
    public $criticalThreshold;

    public function mount($companyId, $itemId) {
        $this->company = Company::find($companyId);
        $this->stockItem = CompanyStock::find($itemId);

        $this->itemName = $this->stockItem->item->item->name;
        $this->preferredStockLevel = $this->stockItem->preferred_stock_level;
        $this->warningThreshold = $this->stockItem->warning_threshold;
        $this->criticalThreshold = $this->stockItem->critical_threshold;
    }

    public function save() {
        $data = $this->validate([
            'preferredStockLevel' => ['required', 'numeric', 'min:0'],
            'warningThreshold' => ['required', 'numeric', 'min:0'],
            'criticalThreshold' => ['required', 'numeric', 'min:0'],
        ]);

        $this->stockItem->update([
            'preferred_stock_level' => $data['preferredStockLevel'],
            'warning_threshold' => $data['warningThreshold'],
            'critical_threshold' => $data['criticalThreshold'],
        ]);

        return redirect()->route('company.stock.render', ['companyId' => $this->company->id])->success(__('company.toasts.stock_updated_successfully'));
    }

    public function render()
    {
        return view('livewire.company.edit-stock');
    }
}
