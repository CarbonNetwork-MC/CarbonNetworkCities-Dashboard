<?php

namespace App\Livewire\Admin\Players;

use App\Http\Livewire\Concerns\WithInvalidation;
use App\Models\Plot;
use App\Models\Player;
use Livewire\Component;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class AddPlot extends Component
{
    use WithInvalidation;

    public $player;

    public $plotId;

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();
    }

    public function addPlot() {
        $data = $this->validate([
            'plotId' => ['required', 'string', 'exists:plots,plot_id'],
        ]);

        // Store the plot for rollback in case of failure
        $plot = Plot::where('plot_id', $data['plotId'])->first();
        $originalPlot = $plot->replicate();

        // Check if plot doesn't exist
        if (!$plot) {
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.plot_not_found'));
        }

        // 1. Optimistic update
        $plot->update([
            'owner_uuid' => $this->player->uuid,
        ]);

        // 2. Send invalidate request to Velocity
        /** @var Response $response */
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/plot/{$plot->plot_id}");

        // Immediate failure (request not accepted)
        if ($response->status() !== 202) {
            $this->rollbackPlot($plot, $originalPlot);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.plot_add_failed'));
        }

        $requestId = $response->json()['requestId'];

        // 3. Poll for result
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->rollbackPlot($plot, $originalPlot);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.plot_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toast.players.plot_add_success'));
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
