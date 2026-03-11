<?php

namespace App\Livewire\Company\Tips;

use App\Models\Company;
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
