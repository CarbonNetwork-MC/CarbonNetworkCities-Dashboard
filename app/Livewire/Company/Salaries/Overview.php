<?php

namespace App\Livewire\Company\Salaries;

use App\Models\BankTransaction;
use App\Models\Company;
use Livewire\Component;

class Overview extends Component
{
    public $company;
    public $salaries;
    public $weeks = [];

    public $selectedWeek;

    public function mount($companyId)
    {
        $this->company = Company::where('id', $companyId)->firstOrFail();
        $this->weeks = $this->company->salaries()
            ->get()
            ->unique('week')
            ->pluck('week')
            ->values()
            ->toArray();

        $this->selectedWeek = in_array(now()->weekOfYear, $this->weeks) ? now()->weekOfYear : $this->weeks[0] ?? null;

        $this->salaries = $this->company->salaries()
            ->where('week', $this->selectedWeek)
            ->get();
    }

    public function updated($key, $value) {
        if ($key === 'selectedWeek') {
            $this->salaries = $this->company->salaries()
            ->where('week', $this->selectedWeek)
            ->get();
        }
    }

    public function markPaid($salaryId) {
        $salary = $this->company->salaries()->where('id', $salaryId)->firstOrFail();
        $salary->status = 'completed';
        $salary->paid_at = now();
        $salary->save();

        $this->salaries = $this->company->salaries()
            ->where('week', $this->selectedWeek)
            ->get();
    }

    public function markUnpaid($salaryId) {
        $salary = $this->company->salaries()->where('id', $salaryId)->firstOrFail();
        $salary->status = 'unpaid';
        $salary->paid_at = null;
        $salary->save();

        $this->salaries = $this->company->salaries()
            ->where('week', $this->selectedWeek)
            ->get();
    }

    public function transferSalary($salaryId) {
        $salary = $this->company->salaries()->where('id', $salaryId)->firstOrFail();

        $companyMainAccount = $this->company->bankAccounts()->where('is_main', true)->first();
        $employeeMainAccount = $salary->player->bankAccounts()->where('type', 'checking')->first();

        $companyMainAccount->balance -= $salary->amount;
        $companyMainAccount->save();

        $employeeMainAccount->balance += $salary->amount;
        $employeeMainAccount->save();

        BankTransaction::create([
            'from_company_id' => $this->company->id,
            'from_company_name' => $this->company->name,
            'to_personal_id' => $employeeMainAccount->id,
            'to_player_name' => $salary->player->username,
            'amount' => $salary->amount,
            'currency' => $this->company->country->currency,
            'description' => 'Salary payment for week ' . $salary->week . ' from ' . $this->company->name,
            'transaction_type' => 'company_to_personal'
        ]);

        $salary->status = 'transfered';
        $salary->paid_at = now();
        $salary->save();

        $this->salaries = $this->company->salaries()
            ->where('week', $this->selectedWeek)
            ->get();
    }

    public function render()
    {
        return view('livewire.company.salaries.overview');
    }
}
