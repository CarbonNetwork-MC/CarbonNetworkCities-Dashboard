<?php

namespace App\Livewire\Admin\Players;

use App\Models\Player;
use App\Services\PluginAPI\ApiService;
use Livewire\Component;

class AddPrefix extends Component
{
    public $player;

    public $prefix;
    public $selected = false;

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();
    }

    public function addPrefix(ApiService $apiService) {
        $data = $this->validate([
            'prefix' => ['required', 'string', 'max:20'],
        ]);

        // Store the current selected prefix (if any) for rollback in case of failure
        $originalSelectedPrefix = $this->player->prefixes()->where('selected', true)->first();

        // 1. Create Prefix
        $prefix = $this->player->prefixes()->create([
            'player_uuid' => $this->player->uuid,
            'prefix' => $data['prefix'],
            'selected' => $this->selected,
        ]);

        // 2. If selected, set all other prefixes to not selected
        if ($this->selected) {
            $this->player->prefixes()->where('id', '!=', $prefix->id)->update(['selected' => false]);
        }

        // 3. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/player/{$this->player->uuid}");
        if (!$success) {
            $this->rollbackPrefix($prefix, $originalSelectedPrefix);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.prefix_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toast.players.prefix_add_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.add-prefix');
    }

    private function rollbackPrefix($prefix, $originalSelectedPrefix)
    {
        $prefix->delete();

        if ($originalSelectedPrefix) {
            $this->player->prefixes()->where('id', '!=', $originalSelectedPrefix->id)->update(['selected' => false]);
            $originalSelectedPrefix->update(['selected' => true]);
        }
    }
}