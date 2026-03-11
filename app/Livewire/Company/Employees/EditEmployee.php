<?php

namespace App\Livewire\Company\Employees;

use App\Models\Company;
use Livewire\Component;

class EditEmployee extends Component
{
    public $company;
    public $employee;

    public $salaryPercentage;
    public $isPaid;

    public function mount($companyId, $employeeId) {
        $this->company = Company::where('id', $companyId)->firstOrFail();
        $this->employee = $this->company->employees()->where('player_uuid', $employeeId)->firstOrFail();

        $this->salaryPercentage = $this->employee->salary_percentage;
        $this->isPaid = $this->employee->is_paid;
    }

    public function save() {
        $data = $this->validate([
            'salaryPercentage' => 'required|numeric|min:0|max:100',
            'isPaid' => 'required|boolean',
        ]);

        $this->employee->salary_percentage = $data['salaryPercentage'];
        $this->employee->is_paid = $data['isPaid'];
        $this->employee->save();

        return redirect()->route('company.employees.render', ['companyId' => $this->company->id])->success(__('company.toasts.employee_updated'));
    }

    public function syncDefaultSalaryPercentage() {
        $this->salaryPercentage = $this->company->settings->default_salary_percentage;
    }

    public function render()
    {
        return view('livewire.company.employees.edit-employee');
    }
}
