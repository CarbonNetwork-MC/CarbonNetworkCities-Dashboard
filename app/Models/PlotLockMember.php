<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlotLockMember extends Model
{
    protected $fillable = [
        'plot_lock_id',
        'player_uuid',
    ];

    public function plotLock(): BelongsTo
    {
        return $this->belongsTo(PlotLock::class, 'plot_lock_id', 'id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }
}
