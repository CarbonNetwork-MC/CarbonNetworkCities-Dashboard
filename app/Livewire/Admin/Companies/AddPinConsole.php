<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Country;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class AddPinConsole extends Component
{
    use WithInvalidation;

    public $company;

    public $countries;

    public $companyId;
    public $accountId;
    public $x;
    public $y;
    public $z;
    public $city;
    public $country;
    public $worldId;
    public $isActive = true;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
        $this->countries = Country::all();
    }

    public function addPinConsole() {
        $data = $this->validate([
            'companyId' => ['required', 'string', 'max:255'],
            'accountId' => ['required', 'string', 'max:255'],
            'x' => ['required', 'numeric'],
            'y' => ['required', 'numeric'],
            'z' => ['required', 'numeric'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255', 'exists:countries,id'],
            'worldId' => ['required', 'string', 'max:255'],
            'isActive' => ['boolean'],
        ]);

        // 1. Create Pin Console
        $pinConsole = $this->company->pinConsoles()->create([
            'company_id' => $this->company->id,
            'account_id' => $data['accountId'],
            'x' => $data['x'],
            'y' => $data['y'],
            'z' => $data['z'],
            'city' => $data['city'],
            'country_id' => $data['country'],
            'world_id' => $data['worldId'],
            'is_active' => $data['isActive'],
        ]);

        // 2. Send invalidate request to Velocity
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/pin-console/{$pinConsole->id}");

        // Immediate failure (request not accepted)
        if ($response->status() !== 202) {
            $pinConsole->delete();
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company.pin_console_add_failed'));
        }

        $requestId = $response->json('requestId');

        // 3. Poll for result
        $success = $this->waitForInvalidationResult($requestId);
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
