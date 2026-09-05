<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'player_uuid',
    'past_username',
])]
class PlayerPastUsername extends Model
{
    public function player(): BelongsTo {
        return $this->belongsTo(Player::class, 'player_uuid');
    }
}
