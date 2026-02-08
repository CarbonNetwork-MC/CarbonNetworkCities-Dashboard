<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesaleItem extends Model
{
    protected $fillable = [
        'item_id',
        'max_amount',
        'price',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
