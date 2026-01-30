<?php

namespace App\Livewire\Admin\Players;

use App\Models\Player;
use Livewire\Component;
use App\Models\ChatColor;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Http\Livewire\Concerns\WithInvalidation;

class AddChatColor extends Component
{
    use WithInvalidation;

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

    public function addChatColor() {
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
        /** @var Response $response */
        $response = Http::withToken(config('services.plugin-api.key'))
            ->post(config('services.plugin-api.url') . "api/invalidate/player/{$this->player->uuid}");

        // Immediate failure (request not accepted)
        if ($response->status() !== 202) {
            $this->rollbackChatColor($chatColor, $originalSelectedColor);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.chat_color_add_failed'));
        }

        $requestId = $response->json()['requestId'];

        // 4. Poll for result
        $success = $this->waitForInvalidationResult($requestId);
        if (!$success) {
            $this->rollbackChatColor($chatColor, $originalSelectedColor);
            return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->error(__('admin.toast.players.chat_color_add_failed'));
        }

        // 5. Success
        return redirect()->route('admin.players.edit', ['uuid' => $this->player->uuid])->success(__('admin.toast.players.chat_color_add_success'));
    }

    public function render()
    {
        return view('livewire.admin.players.add-chat-color');
    }
}
