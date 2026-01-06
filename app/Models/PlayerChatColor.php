<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerChatColor extends Model
{
    protected $filable = [
        'player_uuid',
        'level',
        'level_selected',
        'prefix',
        'prefix_selected',
        'chat',
        'chat_selected',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }
}
