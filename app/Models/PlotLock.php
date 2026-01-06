<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlotLock extends Model
{
    protected $fillable = [
        'plot_id',
        'x',
        'y',
        'z',
    ];

    public function plot(): BelongsTo
    {
        return $this->belongsTo(Plot::class, 'plot_id', 'plot_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(PlotLockMember::class, 'id', 'plot_lock_id');
    }
}
