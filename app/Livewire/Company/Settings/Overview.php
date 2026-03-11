<?php

namespace App\Livewire\Company\Settings;

use App\Models\Company;
use Livewire\Component;

class Overview extends Component
{
    public $company;

    public $salaryScheme = 'own_sales_percentage';
    public $tipScheme = 'per_employee';
    public $defaultSalaryPercentage = 15;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->firstOrFail();
        $this->salaryScheme = $this->company->settings->salary_scheme;
        $this->tipScheme = $this->company->settings->tip_scheme;
        $this->defaultSalaryPercentage = $this->company->settings->default_salary_percentage;
    }

    public function save() {
        $data = $this->validate([
            'salaryScheme' => 'required|in:own_sales_percentage,team_sales_percentage',
            'tipScheme' => 'required|in:per_employee,shared',
            'defaultSalaryPercentage' => 'required|numeric|min:0|max:100',
        ]);

        $this->company->settings->update([
            'salary_scheme' => $data['salaryScheme'],
            'tip_scheme' => $data['tipScheme'],
            'default_salary_percentage' => $data['defaultSalaryPercentage'],
        ]);

        return redirect()->route('company.dashboard.render', ['companyId' => $this->company->id])->success(__('company.toasts.settings_updated'));
    }

    public function render()
    {
        return view('livewire.company.settings.overview');
    }
}
