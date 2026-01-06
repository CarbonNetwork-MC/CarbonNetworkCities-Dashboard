<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $fillable = [
        'uuid',
        'username',
        'level',
        'nationality',
        'onboarding',
        'onboarding_step',
        'selected_language',
        'playtime',
        'updated_playtime_at',
        'last_region_id',
        'last_login',
        'last_logout',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(AccountLink::class, 'player_uuid', 'uuid');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(PersonalBankaccount::class, 'player_uuid', 'uuid');
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class, 'owner_uuid', 'uuid');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'owner_uuid', 'uuid');
    }

    public function employeeAt(): HasMany
    {
        return $this->hasMany(Employee::class, 'player_uuid', 'uuid');
    }

    public function chatColors(): BelongsTo
    {
        return $this->belongsTo(PlayerChatColor::class, 'player_uuid', 'uuid');
    }

    public function prefixes(): BelongsTo
    {
        return $this->belongsTo(PlayerPrefix::class, 'player_uuid', 'uuid');
    }

    public function pastUsernames(): HasMany
    {
        return $this->hasMany(PlayerPastUsername::class, 'player_uuid', 'uuid');
    }
}
