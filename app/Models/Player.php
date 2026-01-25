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

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nationality', 'id');
    }

    public function selectedLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'selected_language', 'id');
    }

    /**
     * Get playtime in human readable format
     * 
     * @return string
     */
    public function getReadablePlaytimeAttribute()
    {
        if (!$this->playtime || $this->playtime <= 0) {
            return '0s';
        }

        $seconds = $this->playtime;
        $units = [
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1,
        ];

        $result = [];

        foreach ($units as $name => $divisor) {
            $quot = intval($seconds / $divisor);
            if ($quot) {
                $result[] = $quot . substr($name, 0, 1); // 'd', 'h', 'm', 's'
                $seconds -= $quot * $divisor;
            }
        }

        return implode(' ', $result);
    }
}
