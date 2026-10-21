<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'uuid',
    'username',
    'level',
    'playtime',
    'country_id',
    'language_id',
    'last_region_id',
    'last_login',
    'last_logout',
])]
class Player extends Model
{
    /** @return BelongsTo<Country, $this> */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /** @return BelongsTo<Language, $this> */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /** @return HasMany<PlayerPastUsername, $this> */
    public function pastUsernames(): HasMany
    {
        return $this->hasMany(PlayerPastUsername::class, 'player_uuid');
    }

    /** @return HasMany<PersonalBankAccount, $this> */
    public function personalBankAccounts(): HasMany
    {
        return $this->hasMany(PersonalBankAccount::class, 'player_uuid', 'uuid');
    }

    /** @return HasMany<PlayerGrant, $this> */
    public function grants(): HasMany
    {
        return $this->hasMany(PlayerGrant::class, 'player_uuid', 'uuid');
    }

    /** @return HasOne<PlayerInventory, $this> */
    public function inventory(): HasOne
    {
        return $this->hasOne(PlayerInventory::class, 'uuid', 'uuid');
    }
}
