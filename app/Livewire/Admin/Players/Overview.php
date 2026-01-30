<?php

namespace App\Livewire\Admin\Players;

use App\Models\Player;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\DB;

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

    public function destroyPlayer(ApiService $apiService) {
        if (!$this->playerToRemove) return;

        $player = $this->playerToRemove;

        // Phase 1: mark intent (DO NOT DELETE YET)
        DB::transaction(function () use ($player) {
            $player->update([
                'deletion_pending_at' => now(),
            ]);
        });

        // Phase 2: notify plugin
        [$status, $success] = $apiService->post(
            "api/invalidate/player/{$player->uuid}"
        );

        if (!$success) {
            // Rollback intent
            $player->update(['deletion_pending_at' => null]);

            // Force reload player in plugin to avoid inconsistencies
            [$status, $ignored] = $apiService->post(
                "api/invalidate/player/{$player->uuid}"
            );

            Toaster::error(__('admin.toast.players.delete_failed'));
            return;
        }

        // Phase 3: finalize deletion
        DB::transaction(function () use ($player) {
            $player->prefixes()->delete();
            $player->chatColors()->delete();
            $player->bankAccounts()->delete();

            $player->delete(); // soft delete (or forceDelete if you insist)
        });

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
