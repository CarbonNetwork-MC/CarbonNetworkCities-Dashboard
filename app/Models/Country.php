<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
        'iso',
        'code',
        'headdb_id',
        'currency',
        'currency_symbol',
        'currency_before_amount',
    ];

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class, 'country_id', 'id');
    }

    public function cityRegions(): HasMany
    {
        return $this->hasMany(CityRegion::class, 'country_id', 'id');
    }
}
