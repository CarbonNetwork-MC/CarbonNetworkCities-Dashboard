<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Country;
use Livewire\Component;
use App\Models\CompanyBankaccount;
use App\Services\RedisService;

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

    public function addPinConsole(RedisService $redisService) {
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

        // 2. Send invalidate request to the Minecraft servers
        $success = $redisService->invalidate('INVALIDATE_PIN_CONSOLE', (string) $pinConsole->id);
        if (!$success) {
            $pinConsole->delete();
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.companies.pin_console_add_failed'));
        }

        // 3. Success
        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.companies.pin_console_added'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-pin-console');
    }
}
