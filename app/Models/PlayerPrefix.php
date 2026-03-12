<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerPrefix extends Model
{
    protected $fillable = [
        'player_uuid',
        'prefix',
        'selected',
    ];
    protected $casts = [
        'selected' => 'boolean',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }
}
