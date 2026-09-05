<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'uuid',
    'username',
    'level',
    'playtime',
    'country_id',
    'language_id',
    'last_region_id',
    'last_login',
    'last_logout'
])]
class Player extends Model
{
    public function country(): BelongsTo {
        return $this->belongsTo(Country::class, 'country_id');
    }
    
    public function language(): BelongsTo {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function pastUsernames(): HasMany {
        return $this->hasMany(PlayerPastUsername::class, 'player_uuid');
    }
}
