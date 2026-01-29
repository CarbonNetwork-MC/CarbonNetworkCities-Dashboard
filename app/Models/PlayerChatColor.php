<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlayerChatColor extends Model
{
    protected $filable = [
        'player_uuid',
        'color_id',
        'type',
        'selected',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ChatColor::class, 'id', 'color_id');
    }
}
