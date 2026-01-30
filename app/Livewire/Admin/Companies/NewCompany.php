<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Player;
use App\Models\Company;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class NewCompany extends Component
{    
    public $companyName;
    public $cocNumber;
    public $worldId;
    public $selectedPlayer;

    public $players;

    public function mount() {
        $this->players = Player::orderBy('username')->get(['uuid', 'username']);
    }

    public function createCompany(ApiService $apiService) {
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

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/company/{$company->id}");

        // Immediate failure (did not accept request)
        if ($status !== 202) {
            $company->delete();
            return redirect()->route('admin.companies.new')->error(__('admin.toast.company.create_failed'));
        }

        // 3. Poll for result
        if (!$success) {
            $company->delete();
            return redirect()->route('admin.companies.new')->error(__('admin.toast.company.create_failed'));
        }

        // 4. Success
        return redirect()->route('admin.companies.render')->success(__('admin.toast.company.created'));
    }

    public function render()
    {
        return view('livewire.admin.companies.new-company');
    }
}