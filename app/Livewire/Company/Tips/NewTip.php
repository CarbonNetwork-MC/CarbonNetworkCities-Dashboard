<?php

namespace App\Livewire\Company\Tips;

use App\Models\Company;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryUpdate;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NewTip extends Component
{
    public $company;

    public $customer;
    public $amount;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->first();
    }

    public function save() {
        $this->validate([
            'customer' => ['required', 'exists:players,username'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $customer = Player::where('username', $this->customer)->first();
        if (!$customer) {
            $this->addError('customer', __('company.validation.customer_not_found'));
            return;
        }

        $tip = $this->company->tips()->create([
            'employee_uuid' => auth()->user()->player->uuid,
            'customer_uuid' => $customer->uuid,
            'amount' => $this->amount,
        ]);

        $tip_scheme = $this->company->settings->tip_scheme;
        if ($tip_scheme == 'per_employee') {
            $existingSalary = $this->company->salaries()
                ->where('player_uuid', Auth::user()->player->uuid)
                ->where('year', now()->year)
                ->where('week', now()->weekOfYear)
                ->where('status', 'unpaid')
                ->first();
            if (!$existingSalary) {
                $existingSalary = EmployeeSalary::create([
                    'company_id' => $this->company->id,
                    'player_uuid' => Auth::user()->player->uuid,
                    'year' => now()->year,
                    'week' => now()->weekOfYear,
                    'amount' => $this->amount,
                ]);
            } else {
                $existingSalary->amount += $this->amount;
                $existingSalary->save();
            }

            EmployeeSalaryUpdate::create([
                'salary_id' => $existingSalary->id,
                'player_uuid' => Auth::user()->player->uuid,
                'tip_id' => $tip->id,
                'amount' => $this->amount,
            ]);
        } else {
            $splitAmount = floor($this->amount / $this->company->employees()->count() * 100) / 100;

            // Employees
            foreach ($this->company->employees as $employee) {
                $existingSalary = $this->company->salaries()
                    ->where('player_uuid', $employee->player_uuid)
                    ->where('year', now()->year)
                    ->where('week', now()->weekOfYear)
                    ->where('status', 'unpaid')
                    ->first();
                if (!$existingSalary) {
                    $existingSalary = EmployeeSalary::create([
                        'company_id' => $this->company->id,
                        'player_uuid' => $employee->player_uuid,
                        'year' => now()->year,
                        'week' => now()->weekOfYear,
                        'amount' => $splitAmount,
                    ]);
                } else {
                    $existingSalary->amount += $splitAmount;
                    $existingSalary->save();
                }

                EmployeeSalaryUpdate::create([
                    'salary_id' => $existingSalary->id,
                    'player_uuid' => $employee->player_uuid,
                    'tip_id' => $tip->id,
                    'amount' => $splitAmount,
                ]);
            }
        }

        return redirect()->route('company.tips.render', ['companyId' => $this->company->id, 'tipId' => $tip->id])->success('company.toasts.tip_created');
    }

    public function render() {
        return view('livewire.company.tips.new-tip');
    }
}
