<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityRegion extends Model
{
    protected $fillable = [
        'internal_name',
        'display_name',
        'country_id',
        'city',
        'world_id',
        'min_x',
        'min_y',
        'min_z',
        'max_x',
        'max_y',
        'max_z',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
