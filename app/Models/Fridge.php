<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fridge extends Model
{
    protected $fillable = [
        'world_id',
        'plot_id',
        'type',
        'min_x',
        'min_y',
        'min_z',
        'max_x',
        'max_y',
        'max_z',
    ];

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'plot_id');
    }
}
