<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WholesaleItem extends Model
{
    protected $fillable = [
        'wholesaler_id',
        'item_id',
        'max_amount',
        'price',
        'sellable',
    ];

    protected $casts = [
        'sellable' => 'boolean',
    ];

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class, 'wholesaler_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
