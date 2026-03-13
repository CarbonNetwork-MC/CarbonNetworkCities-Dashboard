<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Player;
use App\Services\PlayerPermissionService;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class EditEmployee extends Component
{
    public $company;
    public $player;
    public $employee;

    public $roles = [
        'employee',
        'manager',
    ];

    public $role;

    public function mount($companyId, $playerUuid) {
        $this->company = Company::where('id', $companyId)->firstOrFail();
        $this->player = Player::where('uuid', $playerUuid)->firstOrFail();

        $this->employee = $this->company->employees()->where('player_uuid', $playerUuid)->firstOrFail();
        $this->role = $this->employee->role;
    }

    public function updateEmployee(ApiService $apiService, PlayerPermissionService $permissionService) {
        $originalRole = $this->employee->role;    

        $data = $this->validate([
            'role' => 'required|in:employee,manager',
        ]);

        $this->employee->role = $data['role'];
        $this->employee->save();

        $permissionService->syncWholesaleOrderPermission($this->player);

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/company/{$this->company->id}");

        // Immediate failure (request not accepted)
        if ($status !== 202) {
            // Rollback
            $this->rollbackEmployee($originalRole);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toasts.companies.employee_edit_failed'));
        }

        // 3. Poll for result
        if (!$success) {
            // Rollback
            $this->rollbackEmployee($originalRole);
            return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->error(__('admin.toasts.companies.employee_edit_failed'));
        }

        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.toasts.companies.employee_updated'));
    }

    public function render()
    {
        return view('livewire.admin.companies.edit-employee');
    }

    private function rollbackEmployee($originalRole) {
        $this->employee->role = $originalRole;
        $this->employee->save();

        app(PlayerPermissionService::class)->syncWholesaleOrderPermission($this->player);
    }
}
