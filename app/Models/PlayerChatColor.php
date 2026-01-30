<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerChatColor extends Model
{
    protected $fillable = [
        'player_uuid',
        'color_id',
        'type',
        'selected',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(ChatColor::class, 'color_id', 'id');
    }
}
