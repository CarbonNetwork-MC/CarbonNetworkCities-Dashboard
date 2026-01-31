<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemGroup extends Model
{
    protected $fillable = [
        'coc_type',
        'item_id',
        'sellable',
    ];

    public function cocType(): BelongsTo
    {
        return $this->belongsTo(CoCType::class, 'coc_type', 'name');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
