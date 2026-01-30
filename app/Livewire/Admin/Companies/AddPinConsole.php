<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Country;
use Livewire\Component;
use App\Models\CompanyBankaccount;
use App\Services\PluginAPI\ApiService;

class AddPinConsole extends Component
{
    public $company;

    public $accounts;
    public $countries;
    
    public $accountId;
    public $x;
    public $y;
    public $z;
    public $city;
    public $countryId;
    public $worldId;
    public $isActive = true;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
        $this->countries = Country::get(['id', 'name']);
        $this->accounts = CompanyBankaccount::where('company_id', $this->company->id)->get(['id']);
    }

    public function addPinConsole(ApiService $apiService) {
        $data = $this->validate([
            'accountId' => ['required', 'integer'],
            'x' => ['required', 'numeric'],
            'y' => ['required', 'numeric'],
            'z' => ['required', 'numeric'],
            'city' => ['required', 'string', 'max:255'],
            'countryId' => ['required', 'integer', 'exists:countries,id'],
            'worldId' => ['required', 'string', 'max:50'],
            'isActive' => ['boolean'],
        ]);

        // 1. Create PIN Console
        $pinConsole = $this->company->pinConsoles()->create([
            'company_id' => $this->company->id,
            'account_id' => $data['accountId'],
            'x' => $data['x'],
            'y' => $data['y'],
            'z' => $data['z'],
            'city' => $data['city'],
            'country_id' => $data['countryId'],
            'world_id' => $data['worldId'],
            'is_active' => $data['isActive'],
        ]);

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/pin-console/{$pinConsole->id}");

        // Immediate failure (request not accepted)
        if ($status !== 202) {
            $pinConsole->delete();
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company.pin_console_add_failed'));
        }

        // 3. Poll for result
        if (!$success) {
            $pinConsole->delete();
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company.pin_console_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.company.pin_console_added'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-pin-console');
    }
}
