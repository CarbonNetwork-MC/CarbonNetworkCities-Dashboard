<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemGroup extends Model
{
    protected $fillable = [
        'name',
        'coc_type',
        'sellable',
    ];
    protected $casts = [
        'sellable' => 'boolean',
    ];

    public function cocType(): BelongsTo
    {
        return $this->belongsTo(CoCType::class, 'coc_type', 'id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'item_group_items', 'item_group_id', 'item_id')
            ->withPivot(['price', 'base_price', 'sellable', 'max_wholesale_amount'])
            ->withTimestamps();
    }
}
