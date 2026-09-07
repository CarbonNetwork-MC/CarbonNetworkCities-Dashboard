<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'player_uuid',
    'color_id',
    'type',
    'selected'
])]
class PlayerChatColor extends Model
{
    public function color(): BelongsTo {
        return $this->belongsTo(ChatColor::class, 'color_id');
    }

    public function player(): BelongsTo {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }
}
