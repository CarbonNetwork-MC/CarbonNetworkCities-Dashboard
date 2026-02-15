<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyBankaccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class StockOverview extends Component
{
    public $company;

    public $hasPermission = false;

    public $currencySymbol = '';

    public $selectedStock = null;
    public $selectedStockQuantity = 0;
    public $showUpdateStockModal = false;

    public function mount($companyId) {
        $this->company = Company::with(['items', 'stock'])->findOrFail($companyId);

        $player = Auth::user()->player;
        $this->hasPermission = Auth::user()->hasRole('Superadmin')
            || $player->uuid == $this->company->owner_uuid
            || $this->company->employees()->where('player_uuid', $player->uuid)->first()->role == 'manager';

        $this->currencySymbol = CompanyBankaccount::where('company_id', $this->company->id)->where('is_main', true)->first()->country->currency_symbol ?? '';
    }

    public function openUpdateStockModal($itemId) {
        $this->selectedStock = $this->company->stock()->where('item_id', $itemId)->first();
        $this->selectedStockQuantity = $this->selectedStock->quantity ?? 0;
        $this->showUpdateStockModal = true;
    }

    public function saveStock() {
        if (!$this->selectedStock) return;

        $this->selectedStock->update(['quantity' => $this->selectedStockQuantity]);

        $this->reset(['selectedStock', 'selectedStockQuantity', 'showUpdateStockModal']);

        Toaster::success(__('company.toasts.stock_updated'));
    }

    public function render()
    {
        return view('livewire.company.stock-overview');
    }
}
