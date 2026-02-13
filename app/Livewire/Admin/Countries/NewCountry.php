<?php

namespace App\Livewire\Admin\Countries;

use App\Models\Country;
use Livewire\Component;
use App\Services\PluginAPI\ApiService;

class NewCountry extends Component
{
    public $countryName;
    public $iso;
    public $code;
    public $headdbId;
    public $currency;
    public $currencySymbol;
    public $currencyBeforeAmount;

    public function createCountry(ApiService $apiService) {
        $data = $this->validate([
            'countryName' => ['required', 'string', 'max:100'],
            'iso' => ['required', 'string', 'max:2'],
            'code' => ['required', 'string', 'max:10'],
            'headdbId' => ['nullable', 'integer'],
            'currency' => ['nullable', 'string', 'max:5'],
            'currencySymbol' => ['nullable', 'string', 'max:5'],
            'currencyBeforeAmount' => ['required', 'in:0,1'],
        ]);

        $country = Country::create([
            'name' => $data['countryName'],
            'iso' => strtoupper($data['iso']),
            'code' => $data['code'],
            'headdb_id' => $data['headdbId'],
            'currency' => strtoupper($data['currency']),
            'currency_symbol' => $data['currencySymbol'],
            'currency_before_amount' => $data['currencyBeforeAmount'],
        ]);

        [$status, $success] = $apiService->post("api/reload/countries");

        if ($status !== 202) {
            $country->delete();
            return redirect()->route('admin.countries.new')->error(__('admin.toasts.countries.reload_countries_api_error'));
        }

        if (!$success) {
            $country->delete();
            return redirect()->route('admin.countries.new')->error(__('admin.toasts.countries.reload_countries_api_error'));
        }

        return redirect()->route('admin.countries.render')->success(__('admin.toasts.countries.created'));
    }

    public function render()
    {
        return view('livewire.admin.countries.new-country');
    }
}
