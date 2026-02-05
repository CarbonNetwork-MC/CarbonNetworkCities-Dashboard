<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlotMember extends Model
{
    protected $fillable = [
        'plot_id',
        'player_uuid',
        'username',
    ];

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'plot_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }
}
