<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemGroupItem extends Model
{
    protected $fillable = [
        'item_group_id',
        'item_id',
        'price',
        'base_price',
        'sellable',
    ];

    public function itemGroup(): BelongsTo
    {
        return $this->belongsTo(ItemGroup::class, 'item_group_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
