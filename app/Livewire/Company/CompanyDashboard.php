<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\CompanyBankaccount;
use App\Models\CompanyStock;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyDashboard extends Component
{
    use WithPagination;

    public $company;

    public $hasPermission = false;

    public function mount($companyId) {
        $this->company = Company::find($companyId);

        $user = Auth::user();
        $this->hasPermission = $user->hasRole('Superadmin')
            || $user->player->uuid == $this->company->owner_uuid
            || $this->company->employees()->where('player_uuid', $user->player->uuid)->first()->role == 'manager';
    }

    public function render()
    {
        $companyStock = CompanyStock::where('company_id', $this->company->id)
            ->with('item.item')
            ->paginate(9, ['*'], 'stock_page');

        $employees = Employee::where('company_id', $this->company->id)
            ->with('player')
            ->orderByRaw("
                CASE role
                    WHEN 'manager' THEN 1
                    WHEN 'employee' THEN 2
                    ELSE 3
                END
            ")
            ->paginate(7, ['*'], 'employees_page');

        $bankAccounts = CompanyBankaccount::where('company_id', $this->company->id)
            ->with('country')
            ->get();

        return view('livewire.company.company-dashboard', [
            'companyStock' => $companyStock,
            'employees' => $employees,
            'bankAccounts' => $bankAccounts,
        ]);
    }

    private function getCompanyEmployees() {
        $employees = collect();

        $owner = $this->company->owner;
        if ($owner != null) {
            $employees->push([
                'uuid' => $owner->uuid,
                'username' => $owner->username,
                'role' => 'owner',
            ]);
        }

        $this->employeesList->each(function ($employee) use ($employees) {
            $player = $employee->player;
            if ($player != null) {
                $employees->push([
                    'uuid' => $player->uuid,
                    'username' => $player->username,
                    'role' => $employee->role,
                ]);
            }
        });

        $employees = $employees->sortBy(function ($employee) {
            switch ($employee['role']) {
                case 'owner': return 0;
                case 'manager': return 1;
                case 'employee': return 2;
                default: return 3;
            }
        })->values();

        return $employees;
    }
}
