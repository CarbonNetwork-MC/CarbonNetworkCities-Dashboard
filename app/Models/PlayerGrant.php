<?php

namespace App\Models;

use App\Enums\PlayerGrantType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'player_uuid',
    'grant_type',
    'item_id',
    'quantity',
    'data',
    'idempotency_key',
    'claimed_at',
])]
class PlayerGrant extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grant_type' => PlayerGrantType::class,
            'data' => 'array',
            'claimed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Player, $this> */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    /** @return BelongsTo<Item, $this> */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
