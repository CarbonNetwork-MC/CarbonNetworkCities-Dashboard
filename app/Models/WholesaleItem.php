<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesaleItem extends Model
{
    protected $fillable = [
        'item_id',
        'max_amount',
        'price',
        'sellable',
    ];

    protected $casts = [
        'sellable' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
