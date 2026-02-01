<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemGroup extends Model
{
    protected $fillable = [
        'name',
        'coc_type',
        'sellable',
    ];

    public function cocType(): BelongsTo
    {
        return $this->belongsTo(CoCType::class, 'coc_type', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemGroupItem::class, 'item_group_id', 'id');
    }
}
