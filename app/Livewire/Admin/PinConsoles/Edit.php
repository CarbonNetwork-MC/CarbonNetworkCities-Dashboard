<?php

namespace App\Livewire\Admin\PinConsoles;

use App\Models\Company;
use App\Models\Country;
use Livewire\Component;
use App\Models\PinConsole;
use App\Models\CompanyBankaccount;
use App\Services\PluginAPI\ApiService;

class Edit extends Component
{
    public $pinConsole;

    public $companyId;
    public $accountId;
    public $x;
    public $y;
    public $z;
    public $city;
    public $countryId;
    public $worldId;
    public $isActive;

    public $company;
    public $companies;
    public $accounts;
    public $countries;

    public function mount($id) {
        $this->pinConsole = PinConsole::findOrFail($id);

        $this->companyId = $this->pinConsole->company_id;
        $this->company = Company::find($this->companyId);
        $this->accounts = CompanyBankaccount::where('company_id', $this->company->id)->get(['id'])->toArray();
        $this->accountId = $this->pinConsole->account_id;
        $this->x = $this->pinConsole->x;
        $this->y = $this->pinConsole->y;
        $this->z = $this->pinConsole->z;
        $this->city = $this->pinConsole->city;
        $this->countryId = $this->pinConsole->country_id;
        $this->worldId = $this->pinConsole->world_id;
        $this->isActive = $this->pinConsole->is_active;

        $this->companies = Company::get(['id', 'name']);
        $this->countries = Country::get(['id', 'name']);
    }

    public function updated($key, $value) {
        if ($key === 'companyId') {
            if ($this->company?->id !== (int) $value) {
                $this->company = Company::find($value);
                $this->accounts = CompanyBankaccount::where('company_id', $value)->get(['id'])->toArray();
                $this->accountId = null;
            }
        }
    }

    public function updatePinConsole(ApiService $apiService) {
        if (!$this->pinConsole) return;
        
        $originalData = $this->pinConsole->toArray();
        
        $data = $this->validate([
            'companyId' => ['required', 'integer'],
            'accountId' => ['required', 'integer'],
            'x' => ['required', 'integer'],
            'y' => ['required', 'integer'],
            'z' => ['required', 'integer'],
            'city' => ['required', 'string', 'max:255'],
            'countryId' => ['required', 'integer', 'exists:countries,id'],
            'worldId' => ['required', 'string', 'max:50'],
            'isActive' => ['boolean'],
        ]);

        $this->pinConsole->company_id = $data['companyId'];
        $this->pinConsole->account_id = $data['accountId'];
        $this->pinConsole->x = $data['x'];
        $this->pinConsole->y = $data['y'];
        $this->pinConsole->z = $data['z'];
        $this->pinConsole->city = $data['city'];
        $this->pinConsole->country_id = $data['countryId'];
        $this->pinConsole->world_id = $data['worldId'];
        $this->pinConsole->is_active = $data['isActive'];
        $this->pinConsole->save();

        [$status, $success] = $apiService->post("api/invalidate/pin-console/{$this->pinConsole->id}");

        if ($status !== 202) {
            $this->rollbackPinConsole($originalData);
            return redirect()->route('admin.pin-consoles.edit', ['id' => $this->pinConsole->id])->error(__('admin.toast.pin_consoles.pin_console_update_failed'));
        }

        if (!$success) {
            $this->rollbackPinConsole($originalData);
            return redirect()->route('admin.pin-consoles.edit', ['id' => $this->pinConsole->id])->error(__('admin.toast.pin_consoles.pin_console_update_failed'));
        }

        return redirect()->route('admin.pin-consoles.render')->success(__('admin.toast.pin_consoles.updated'));
    }

    public function render()
    {
        return view('livewire.admin.pin-consoles.edit');
    }

    private function rollbackPinConsole($originalData) {
        $this->pinConsole->company_id = $originalData['company_id'];
        $this->pinConsole->account_id = $originalData['account_id'];
        $this->pinConsole->x = $originalData['x'];
        $this->pinConsole->y = $originalData['y'];
        $this->pinConsole->z = $originalData['z'];
        $this->pinConsole->city = $originalData['city'];
        $this->pinConsole->country_id = $originalData['country_id'];
        $this->pinConsole->world_id = $originalData['world_id'];
        $this->pinConsole->is_active = $originalData['is_active'];
        $this->pinConsole->save();
    }
}
