<?php

namespace App\Livewire\Admin\Plots;

use App\Models\Plot;
use App\Models\Player;
use App\Services\RedisService;
use Livewire\Component;

class AddMember extends Component
{
    public $plot;

    public $playerUuid;

    public $players;

    public function mount($id) {
        $this->plot = Plot::findOrFail($id);
        $memberUuids = $this->plot->members()->pluck('player_uuid')->toArray();
        $this->players = Player::whereNotIn('uuid', $memberUuids)->where('uuid', '!=', $this->plot->owner_uuid)->get(['uuid', 'username']);
    }

    public function addMember(RedisService $redisService) {
        $data = $this->validate([
            'playerUuid' => 'required|exists:players,uuid',
        ]);

        $player = Player::where('uuid', $data['playerUuid'])->first();

        if (!$player) {
            return redirect()->route('admin.plots.edit', ['id' => $this->plot->id])->error(__('admin.toasts.plots.player_not_found'));
        }

        $this->plot->members()->create([
            'player_uuid' => $data['playerUuid'],
            'username' => $player->username,
        ]);

        $success = $redisService->invalidate('INVALIDATE_PLOT_' . $this->plot->plot_id);
        if (!$success) {
            $this->plot->members()->where('player_uuid', $data['playerUuid'])->delete();
            return redirect()->route('admin.plots.edit', ['id' => $this->plot->id])->error(__('admin.toasts.plots.invalidate_plot_api_error'));
        }

        return redirect()->route('admin.plots.edit', ['id' => $this->plot->id])->success(__('admin.toasts.plots.member_added'));
    }

    public function render()
    {
        if ($this->getErrorBag()->isNotEmpty()) {
            logger()->debug('Validation errors', $this->getErrorBag()->toArray());
        }
        return view('livewire.admin.plots.add-member');
    }
}
