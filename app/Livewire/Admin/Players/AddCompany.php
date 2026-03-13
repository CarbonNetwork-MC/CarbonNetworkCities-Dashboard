<?php

namespace App\Livewire\Admin\Players;

use App\Models\Company;
use App\Models\Player;
use App\Services\PlayerPermissionService;
use App\Services\RedisService;
use Livewire\Component;

class AddCompany extends Component
{
    public $player;

    public $companyId;

    public $companies;

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();

        $this->companies = Company::where('owner_uuid', '!=', $uuid)
            ->orWhereNull('owner_uuid')
            ->get(['id', 'name']);
    }

    public function addCompany(RedisService $redisService) {
        $data = $this->validate([
            'companyId' => ['required', 'exists:companies,id'],
        ]);

        // Store the company to add for rollback in case of failure
        $company = Company::where('id', $data['companyId'])->first();
        $originalCompany = $company->replicate();

        // 1. Optimistic Update
        $company->update([
            'owner_uuid' => $this->player->uuid,
        ]);
        app(PlayerPermissionService::class)->syncWholesaleOrderPermission($this->player);

        // 2. Send invalidate request to the Minecraft servers
        $success = $redisService->invalidate('INVALIDATE_COMPANY', $company->id);
        if (!$success) {
            $this->rollbackCompany($company, $originalCompany);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toasts.players.company_add_failed'));
        }

        // 3. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toasts.players.company_add_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.add-company');
    }

    private function rollbackCompany($company, $originalCompany) {
        $company->update([
            'owner_uuid' => $originalCompany->owner_uuid,
        ]);
        app(PlayerPermissionService::class)->syncWholesaleOrderPermission($this->player);
    }
}
