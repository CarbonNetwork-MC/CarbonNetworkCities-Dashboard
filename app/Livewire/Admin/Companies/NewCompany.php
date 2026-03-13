<?php

namespace App\Livewire\Admin\Companies;

use App\Models\CoCType;
use App\Models\Company;
use App\Models\Player;
use App\Services\RedisService;
use Livewire\Component;

class NewCompany extends Component
{    
    public $companyName;
    public $cocNumber;
    public $cocType;
    public $worldId;
    public $selectedPlayer;

    public $players;
    public $cocTypes;

    public function mount() {
        $this->players = Player::orderBy('username')->get(['uuid', 'username']);
        $this->cocTypes = CoCType::get(['id', 'name']);
    }

    public function createCompany(RedisService $redisService) {
        $data = $this->validate([
            'companyName'    => ['required', 'string', 'max:255'],
            'cocNumber'      => ['required', 'string', 'max:20'],
            'worldId'        => ['required', 'string', 'max:255'],
            'selectedPlayer' => ['nullable', 'string', 'exists:players,uuid'],
        ]);

        // 1. Optimistic create
        $company = Company::create([
            'name'       => $data['companyName'],
            'world_id'   => $data['worldId'],
            'coc_number' => $data['cocNumber'],
            'owner_uuid' => $data['selectedPlayer'],
        ]);

        // 2. Send invalidate request to the Minecraft servers
        $success = $redisService->invalidate('INVALIDATE_COMPANY', $company->id);
        if (!$success) {
            $company->delete();
            return redirect()->route('admin.companies.new')->error(__('admin.toasts.companies.create_failed'));
        }

        // 3. Success
        return redirect()->route('admin.companies.render')->success(__('admin.toast.companies.created'));
    }

    public function render()
    {
        return view('livewire.admin.companies.new-company');
    }
}