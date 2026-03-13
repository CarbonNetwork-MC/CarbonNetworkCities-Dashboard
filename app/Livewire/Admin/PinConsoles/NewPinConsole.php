<?php

namespace App\Livewire\Admin\PinConsoles;

use App\Models\Company;
use App\Models\Country;
use App\Services\RedisService;
use App\Models\CompanyBankaccount;
use Livewire\Component;

class NewPinConsole extends Component
{
    public $companies;
    public $accounts;
    public $countries;

    public $company;

    public $companyId;
    public $accountId;
    public $x;
    public $y;
    public $z;
    public $city;
    public $countryId;
    public $worldId;
    public $isActive = true;

    public function mount() {
        $this->companies = Company::get(['id', 'name']);
        $this->countries = Country::get(['id', 'name']);
        $this->accounts = collect();
    }

    public function updated($key, $value) {
        if ($key === 'companyId') {
            $this->company = Company::find($value);
            $this->accounts = CompanyBankaccount::where('company_id', $value)->get();
            $this->accountId = null;
        }
    }

    public function createPinConsole(RedisService $redisService) {
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

        // 1. Create PIN Console
        $pinConsole = $this->company->pinConsoles()->create([
            'company_id' => $data['companyId'],
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
        $success = $redisService->invalidate('INVALIDATE_PIN_CONSOLE', $pinConsole->id);
        if (!$success) {
            $pinConsole->delete();
            return redirect()->route('admin.pin-consoles.new')->error(__('admin.toast.pin_consoles.pin_console_create_failed'));
        }

        // 3. Success
        return redirect()->route('admin.pin-consoles.render')->success(__('admin.toast.pin_consoles.created'));
    }

    public function render()
    {
        return view('livewire.admin.pin-consoles.new-pin-console');
    }
}
