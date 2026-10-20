<?php

namespace App\Models;

use App\Models\Concerns\HasImmutableAttributes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'internal_key',
    'display_name',
    'icon_material',
    'created_by_player_uuid',
    'created_by_user_uuid',
])]
class ItemCategory extends Model
{
    use HasImmutableAttributes;

    /**
     * @return list<string>
     */
    protected function immutableAttributes(): array
    {
        return ['internal_key'];
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

    /** @return HasMany<Item, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
