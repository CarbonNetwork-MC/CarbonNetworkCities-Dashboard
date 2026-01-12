<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Models\Player;
use Livewire\Component;

class NewCompany extends Component
{
    public $companyName;
    public $cocNumber;
    public $worldId;
    public $selectedPlayer;

    public $players;

    public function mount() {
        $this->players = Player::orderBy('username')->get();
    }

    public function createCompany() {
        $data = $this->validate([
            'companyName'    => ['required', 'string', 'max:255'],
            'cocNumber'      => ['required', 'string', 'max:20'],
            'worldId'        => ['required', 'string', 'max:255'],
            'selectedPlayer' => ['nullable', 'string', 'exists:players,uuid'],
        ]);

        Company::create([
            'name'       => $data['companyName'],
            'world_id'   => $data['worldId'],
            'coc_number' => $data['cocNumber'],
            'owner_uuid' => $data['selectedPlayer'],
        ]);

        return redirect()->route('admin.companies.render')->success(__('admin.toast.company_created'));
    }

    public function render()
    {
        return view('livewire.admin.companies.new-company');
    }
}