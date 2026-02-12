<?php

namespace App\Livewire\Admin\Players;

use App\Models\Plot;
use App\Models\Player;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class AddPlot extends Component
{
    public $player;

    public $plotId;

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();
    }

    public function addPlot(ApiService $apiService) {
        $data = $this->validate([
            'plotId' => ['required', 'string', 'exists:plots,plot_id'],
        ]);

        // Store the plot for rollback in case of failure
        $plot = Plot::where('plot_id', $data['plotId'])->first();
        $originalPlot = $plot->replicate();

        // Check if plot doesn't exist
        if (!$plot) {
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toasts.players.plot_not_found'));
        }

        // 1. Optimistic update
        $plot->update([
            'owner_uuid' => $this->player->uuid,
        ]);

        // 2. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/plot/{$plot->plot_id}");
        if (!$success) {
            $this->rollbackPlot($plot, $originalPlot);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toasts.players.plot_add_failed'));
        }

        // 3. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toasts.players.plot_add_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.add-plot');
    }

    private function rollbackPlot($plot, $originalPlot) {
        $plot->update([
            'owner_uuid' => $originalPlot->owner_uuid,
        ]);
    }
}
