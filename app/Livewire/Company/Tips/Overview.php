<?php

namespace App\Livewire\Company\Tips;

use App\Models\Company;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryUpdate;
use Livewire\Component;
use Livewire\WithPagination;

class Overview extends Component
{
    use WithPagination;

    public $company;

    public $hasPermission;

    public $tipsPerPage = 20;
    public $tipToDelete;
    public $showDeleteTipModal = false;

    public function mount($companyId) {
        $this->company = Company::where('id', $companyId)->first();
        $this->hasPermission = auth()->user()->hasRole('Superadmin')
            || auth()->user()->player->uuid == $this->company->owner_uuid
            || $this->company->employees()->where('player_uuid', auth()->user()->player->uuid)->first()->role == 'manager';
    }

    public function removeTip($id) {
        $this->tipToDelete = $this->company->tips()->where('id', $id)->first();
        $this->showDeleteTipModal = true;
    }

    public function destroyTip() {
        if (!$this->tipToDelete) return;

        $updates = EmployeeSalaryUpdate::where('tip_id', $this->tipToDelete->id)->get();
        foreach ($updates as $update) {
            $salary = EmployeeSalary::where('id', $update->salary_id)
                ->where('status', 'unpaid')
                ->first();
            if ($salary) {
                $salary->amount -= $update->amount;
                $salary->save();
            }
            $update->delete();
        }

        $this->tipToDelete->delete();
        $this->showDeleteTipModal = false;
        $this->tipToDelete = null;
    }

    public function render()
    {
        $tips = $this->company->tips()
            ->with(['employee', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->tipsPerPage);

        return view('livewire.company.tips.overview', [
            'tips' => $tips,
        ]);
    }
}
