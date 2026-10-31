<?php

namespace App\Models;

use App\Enums\FridgeApplianceType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'uuid',
    'appliance_type',
    'refrigerator_rows',
    'freezer_rows',
    'world',
    'x',
    'y',
    'z',
    'owner_player_uuid',
    'owner_plot_id',
    'inventory_version',
    'revision',
])]
class Fridge extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    protected function casts(): array
    {
        return [
            'appliance_type' => FridgeApplianceType::class,
            'refrigerator_rows' => 'integer',
            'freezer_rows' => 'integer',
            'x' => 'integer',
            'y' => 'integer',
            'z' => 'integer',
            'owner_plot_id' => 'integer',
            'inventory_version' => 'integer',
            'revision' => 'integer',
        ];
    }

    /** @return BelongsTo<Player, $this> */
    public function ownerPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'owner_player_uuid', 'uuid');
    }

    /** @return HasMany<FridgeItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(FridgeItem::class, 'fridge_uuid', 'uuid');
    }

    /** @return HasMany<FridgeInteractionAnchor, $this> */
    public function interactionAnchors(): HasMany
    {
        return $this->hasMany(FridgeInteractionAnchor::class, 'fridge_uuid', 'uuid');
    }
}
