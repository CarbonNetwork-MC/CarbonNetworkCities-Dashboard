<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class AddEmployee extends Component
{
    public $company;

    public $roles = [
        'Employee',
        'Manager',
    ];

    public $playerUuid;
    public $role;

    public function mount($id) {
        $this->company = Company::where('id', $id)->firstOrFail();
    }

    public function addEmployee() {
        $data = $this->validate([
            'playerUuid' => 'required|exists:players,uuid',
            'role' => 'required|in:Employee,Manager',
        ]);

        if ($this->company->employees()->where('player_uuid', $data['playerUuid'])->exists()) {
            return Toaster::error(__('admin.messages.employee_already_assigned'));
        }

        $this->company->employees()->create([
            'player_uuid' => $data['playerUuid'],
            'company_id' => $this->company->id,
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.companies.edit', ['id' => $this->company->id])->success(__('admin.messages.employee_assigned_successfully'));
    }

    public function render()
    {
        return view('livewire.admin.companies.add-employee');
    }
}
