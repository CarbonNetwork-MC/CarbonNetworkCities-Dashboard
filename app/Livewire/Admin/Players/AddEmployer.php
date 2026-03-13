<?php

namespace App\Livewire\Admin\Players;

use App\Models\Player;
use App\Models\Company;
use App\Services\PlayerPermissionService;
use App\Services\RedisService;
use Livewire\Component;

class AddEmployer extends Component
{
    public $player;

    public $companyId;
    public $role;

    public $companies;

    public $roles = [
        'Employee',
        'Manager',
    ];

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();

        $this->companies = Company::where(function ($query) use ($uuid) {
            $query->where('owner_uuid', '!=', $uuid)
                ->orWhereNull('owner_uuid');
        })->whereDoesntHave('employees', function ($query) use ($uuid) {
            $query->where('player_uuid', $uuid);
        })->get(['id', 'name']);
    }

    public function addEmployer(RedisService $redisService, PlayerPermissionService $playerPermission) {      
        $data = $this->validate([
            'companyId' => 'required|exists:companies,id',
            'role' => 'required|in:Employee,Manager',
        ]);

        $company = Company::where('id', $data['companyId'])->first();

        // 1. Optimistic Update
        $company->employees()->create([
            'player_uuid' => $this->player->uuid,
            'company_id' => $data['companyId'],
            'role' => $data['role'],
        ]);
        $playerPermission->syncWholesaleOrderPermission($this->player);

        // 2. Send invalidate request to Velocity
        $success = $redisService->invalidate('INVALIDATE_COMPANY', $company->id);
        if (!$success) {
            $company->employees()->where('player_uuid', $this->player->uuid)->delete();
            $playerPermission->syncWholesaleOrderPermission($this->player);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toasts.players.employer_add_failed'));
        }

        // 3. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toasts.players.employer_add_success'));
    } 

    public function render()
    {
        return view('livewire.admin.players.add-employer');
    }
}
