<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Player;
use App\Models\Company;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class AddEmployee extends Component
{
    use WithInvalidation;
    
    public $company;

    public $roles = [
        'Employee',
        'Manager',
    ];

    public $players;

    public $playerUuid;
    public $role;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();

        $employeesUuids = $this->company->employees()->pluck('player_uuid')->toArray();
        $ownerUuid = $this->company->owner?->uuid;

        $this->players = Player::whereNotIn('uuid', array_filter(array_merge($employeesUuids, [$ownerUuid])))->get();
    }

    public function addEmployee() {
        $data = $this->validate([
            'playerUuid' => 'required|exists:players,uuid',
            'role' => 'required|in:Employee,Manager',
        ]);

        if ($this->company->employees()->where('player_uuid', $data['playerUuid'])->exists()) {
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company.employee_already_assigned'));
        }

        // 1. Create Employee
        $this->company->employees()->create([
            'player_uuid' => $data['playerUuid'],
            'company_id' => $this->company->id,
            'role' => $data['role'],
        ]);

        // 2. Send invalidate request to Velocity
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/company/{$this->company->id}");

        // Immediate failure (request not accepted)
        if ($response->status() !== 202) {
            // Rollback
            $this->company->employees()->where('player_uuid', $data['playerUuid'])->delete();
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company.employee_assign_failed'));
        }

        $requestId = $response->json('requestId');

        // 3. Poll for result
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            // Rollback
            $this->company->employees()->where('player_uuid', $data['playerUuid'])->delete();
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toast.company.employee_assign_failed'));
        }

        // 4. Success
        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toast.company.employee_assigned'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-employee');
    }
}
