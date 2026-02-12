<?php

namespace App\Livewire\Admin\Players;

use App\Models\Player;
use Livewire\Component;
use App\Models\ChatColor;
use App\Services\PluginAPI\ApiService;

class AddChatColor extends Component
{
    public $player;

    public $color;
    public $type;
    public $selected = false;

    public $allChatColors;
    public $types = ['chat', 'level', 'prefix'];

    public function mount($uuid) {
        $this->player = Player::where('uuid', $uuid)->firstOrFail();

        $this->allChatColors = ChatColor::all();
    }

    public function updated($key, $value) {
        if ($key === 'type' && $value) {
            $this->allChatColors = ChatColor::whereNotIn('id', function($query) use ($value) {
                $query->select('color_id')
                      ->from('player_chat_colors')
                      ->where('player_uuid', $this->player->uuid)
                      ->where('type', $value);
            })->get();
        }
    }

    public function addChatColor(ApiService $apiService) {
        $data = $this->validate([
            'color' => ['required', 'string', 'max:20'],
            'type' => ['required', 'in:chat,level,prefix'],
        ]);

        // Store the current selected chat color (if any) for rollback in case of failure
        $originalSelectedColor = $this->player->chatColors()->where('type', $data['type'])->where('selected', true)->first();

        // 1. Create Chat Color
        $chatColor = $this->player->chatColors()->create([
            'player_uuid' => $this->player->uuid,
            'color_id' => $data['color'],
            'type' => $data['type'],
            'selected' => $this->selected,
        ]);

        // 2. If selected, set all other chat colors of the same type to not selected
        if ($this->selected) {
            $this->player->chatColors()->where('type', $data['type'])->where('id', '!=', $chatColor->id)->update(['selected' => false]);
        }

        // 3. Send invalidate request to Velocity
        [$status, $success] = $apiService->post("api/invalidate/player/{$this->player->uuid}");
        if (!$success) {
            $this->rollbackChatColor($chatColor, $originalSelectedColor);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toasts.players.chat_color_add_failed'));
        }

        // 4. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toasts.players.chat_color_add_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.add-chat-color');
    }
}
