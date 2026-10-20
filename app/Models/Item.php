<?php

namespace App\Models;

use App\Models\Concerns\HasImmutableAttributes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'internal_id',
    'category_id',
    'display_name',
    'material',
    'data',
    'created_by_player_uuid',
    'created_by_user_uuid',
])]
class Item extends Model
{
    use HasImmutableAttributes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['data' => 'array'];
    }

    /**
     * @return list<string>
     */
    protected function immutableAttributes(): array
    {
        return ['internal_id'];
    }

    /** @return BelongsTo<ItemCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class);
    }

    /** @return BelongsTo<Player, $this> */
    public function creatorPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'created_by_player_uuid', 'uuid');
    }

    /** @return BelongsTo<User, $this> */
    public function creatorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_uuid', 'uuid');
    }

    /** @return HasMany<PlayerGrant, $this> */
    public function playerGrants(): HasMany
    {
        return $this->hasMany(PlayerGrant::class);
    }
}
