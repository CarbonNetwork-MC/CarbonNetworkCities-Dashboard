<?php

namespace App\Livewire\Admin\Players;

use App\Models\Player;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Overview extends Component
{
    use WithPagination;

    public $search = '';
    public $playersPerPage = 10;

    public $playerToRemove = null;
    public $showRemovePlayerModal = false;

    public function updated($key, $value) {
        if ($key === 'playersPerPage') {
            $this->resetPage();
        }

        if ($key === 'search') {
            $this->resetPage();
        }
    }

    public function removePlayer($uuid) {
        $this->playerToRemove = Player::where('uuid', $uuid)->first();
        $this->showRemovePlayerModal = true;
    }

    public function destroyPlayer() {
        if (!$this->playerToRemove) return;

        $this->playerToRemove->delete();
        $this->playerToRemove = null;
        $this->showRemovePlayerModal = false;

        Toaster::success(__('admin.messages.players.deleted_successfully'));
    }

    public function render()
    {
        return view('livewire.admin.players.overview', [
            'players' => Player::where('username', 'like', '%' . $this->search . '%')->orWhere('uuid', 'like', '%' . $this->search . '%')->paginate($this->playersPerPage),
        ]);
    }
}
