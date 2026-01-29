<?php

namespace App\Livewire\Admin\Players;

use App\Http\Livewire\Concerns\WithInvalidation;
use App\Models\Player;
use Livewire\Component;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class EditPrefix extends Component
{
    use WithInvalidation;

    public $player;
    public $selectedPrefix;

    public $prefix;
    public $selected;

    public function mount($uuid, $id) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();
        $this->selectedPrefix = $this->player->prefixes()->where('id', $id)->firstOrFail();

        $this->prefix = $this->selectedPrefix->prefix;
        $this->selected = $this->selectedPrefix->selected;
    }

    public function updatePrefix() {
        $data = $this->validate([
            'prefix' => ['required', 'string', 'max:20'],
        ]);

        // Store the current selected prefix (if any) and the original values for rollback in case of failure
        $originalSelectedPrefix = $this->player->prefixes()->where('selected', true)->first();
        $originalPrefixValues = $this->selectedPrefix;

        // 1. Optimistic update
        $this->selectedPrefix->update([
            'prefix' => $data['prefix'],
            'selected' => $this->selected,
        ]);

        // 2. If selected, set all other prefixes to not selected
        if ($this->selected) {
            $this->player->prefixes()->where('id', '!=', $this->selectedPrefix->id)->update(['selected' => false]);
        }

        // 3. Send invalidate request to Velocity
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/player/{$this->player->uuid}");

        // Immediate failure (request not accepted)
        /** @var Response $response */
        if ($response->status() !== 202) {
            $this->rollbackPrefix($originalPrefixValues, $originalSelectedPrefix);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.prefix_update_failed'));
        }

        $requestId = $response->json()['requestId'];

        // 4. Poll for result
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->rollbackPrefix($originalPrefixValues, $originalSelectedPrefix);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.prefix_update_failed'));
        }

        // 5. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toast.players.prefix_update_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.edit-prefix');
    }

    private function rollbackPrefix($prefix, $originalSelectedPrefix) {
        // Rollback the updated prefix
        $this->selectedPrefix->update([
            'prefix' => $prefix->prefix,
            'selected' => $prefix->selected,
        ]);

        // Rollback the selected prefix if it was changed
        if ($originalSelectedPrefix) {
            $this->player->prefixes()->where('id', '!=', $this->selectedPrefix->id)->update(['selected' => false]);
            $originalSelectedPrefix->update(['selected' => true]);
        }
    }
}
