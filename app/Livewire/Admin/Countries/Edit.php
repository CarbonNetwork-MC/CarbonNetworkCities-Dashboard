<?php

namespace App\Livewire\Admin\Countries;

use App\Models\Country;
use App\Services\RedisService;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Edit extends Component
{
    public $country;
    public $countryName;
    public $iso;
    public $code;
    public $headdbId;
    public $currency;
    public $currencySymbol;
    public $currencyBeforeAmount;

    public function mount($id) {
        $this->country = Country::findOrFail($id);

        $this->countryName = $this->country->name;
        $this->iso = $this->country->iso;
        $this->code = $this->country->code;
        $this->headdbId = $this->country->headdb_id;
        $this->currency = $this->country->currency;
        $this->currencySymbol = $this->country->currency_symbol;
        $this->currencyBeforeAmount = $this->country->currency_before_amount;
    }

    public function updateCountry(RedisService $redisService) {
        if (!$this->country) return;

        $originalData = $this->country->toArray();

        $data = $this->validate([
            'countryName' => ['required', 'string', 'max:100'],
            'iso' => ['required', 'string', 'max:2'],
            'code' => ['required', 'string', 'max:10'],
            'headdbId' => ['nullable', 'integer'],
            'currency' => ['nullable', 'string', 'max:5'],
            'currencySymbol' => ['nullable', 'string', 'max:5'],
            'currencyBeforeAmount' => ['required', 'in:0,1'],
        ]);

        $this->country->name = $data['countryName'];
        $this->country->iso = strtoupper($data['iso']);
        $this->country->code = $data['code'];
        $this->country->headdb_id = $data['headdbId'];
        $this->country->currency = strtoupper($data['currency']);
        $this->country->currency_symbol = $data['currencySymbol'];
        $this->country->currency_before_amount = $data['currencyBeforeAmount'];
        $this->country->save();

        $success = $redisService->invalidate('RELOAD_COUNTRIES', 'NULL');
        if (!$success) {
            $this->rollbackCountry($originalData);
            Toaster::error(__('admin.toasts.countries.reload_countries_api_error'));
            return;
        }
        
        return redirect()->route('admin.countries.render')->success(__('admin.toasts.countries.updated'));
    }

    public function render()
    {
        return view('livewire.admin.countries.edit');
    }

    private function rollbackCountry(array $originalData): void {
        $this->country->name = $originalData['name'];
        $this->country->iso = $originalData['iso'];
        $this->country->code = $originalData['code'];
        $this->country->headdb_id = $originalData['headdbId'];
        $this->country->currency = $originalData['currency'];
        $this->country->currency_symbol = $originalData['currencySymbol'];
        $this->country->currency_before_amount = $originalData['currencyBeforeAmount'];
        $this->country->save();
    }
}
