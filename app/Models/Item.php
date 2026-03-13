<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'internal_id',
        'name',
        'category_id',
        'material',
        'data',
        'player_uuid',
        'user_uuid',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id', 'id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }

    public function wholesaleItem()
    {
        return $this->hasOne(WholesaleItem::class, 'item_id', 'id');
    }
}
