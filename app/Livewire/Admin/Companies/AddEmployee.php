<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Player;
use App\Models\Company;
use App\Services\PlayerPermissionService;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class AddEmployee extends Component
{    
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

        $this->players = Player::whereNotIn('uuid', array_filter(array_merge($employeesUuids, [$ownerUuid])))->get(['uuid', 'username']);
    }

    public function addEmployee(ApiService $apiService, PlayerPermissionService $permissionService) {
        $data = $this->validate([
            'playerUuid' => 'required|exists:players,uuid',
            'role' => 'required|in:Employee,Manager',
        ]);

        if ($this->company->employees()->where('player_uuid', $data['playerUuid'])->exists()) {
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toasts.companies.employee_already_assigned'));
        }

        // 1. Create Employee
        $this->company->employees()->create([
            'player_uuid' => $data['playerUuid'],
            'company_id' => $this->company->id,
            'role' => $data['role'],
        ]);

        $player = Player::where('uuid', $data['playerUuid'])->first();
        $permissionService->syncWholesaleOrderPermission($player);

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/company/{$this->company->id}");

        // Immediate failure (request not accepted)
        if ($status !== 202) {
            // Rollback
            $this->rollbackEmployee($data['playerUuid']);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toasts.companies.employee_assign_failed'));
        }

        // 3. Poll for result
        if (!$success) {
            // Rollback
            $this->rollbackEmployee($data['playerUuid']);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toasts.companies.employee_assign_failed'));
        }

        // 4. Success
        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toasts.companies.employee_assigned'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-employee');
    }

    private function rollbackEmployee($playerUuid) {
        $this->company->employees()->where('player_uuid', $playerUuid)->delete();

        $player = Player::where('uuid', $playerUuid)->first();
        app(PlayerPermissionService::class)->syncWholesaleOrderPermission($player);
    }
}
