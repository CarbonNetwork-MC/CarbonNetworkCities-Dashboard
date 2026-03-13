<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wholesaler extends Model
{
    protected $fillable = [
        'country_id',
        'name',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(WholesalerEmployee::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WholesaleItem::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(WholesaleOrder::class);
    }
}
