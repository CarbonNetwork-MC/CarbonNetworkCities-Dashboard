<?php

namespace App\Livewire\Company\Employees;

use App\Models\Company;
use App\Models\Player;
use Livewire\Component;

class Employees extends Component
{
    public $company;
    public $employees;

    public function mount($companyId) {
        $this->company = Company::find($companyId);

        $owner = Player::where('uuid', $this->company->owner->uuid)->first();
        $employees = $this->company->employees;

        $this->employees = collect();
        if ($owner != null) {
            $this->employees->push([
                'uuid' => $owner->uuid,
                'username' => $owner->username,
                'role' => 'owner',
            ]);
        }

        $employees->each(function ($employee) {
            $player = Player::where('uuid', $employee->player_uuid)->first();
            if ($player != null) {
                $this->employees->push([
                    'uuid' => $player->uuid,
                    'username' => $player->username,
                    'role' => $employee->role,
                ]);
            }
        });

        // Sort employees by role (owner first, then manager, then employee)
        $this->employees = $this->employees->sortBy(function ($employee) {
            switch ($employee['role']) {
                case 'owner':
                    return 0;
                case 'manager':
                    return 1;
                case 'employee':
                    return 2;
                default:
                    return 3;
            }
        })->values();
    }

    public function render()
    {
        return view('livewire.company.employees.employees');
    }
}
