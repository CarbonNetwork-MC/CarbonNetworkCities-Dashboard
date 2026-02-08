<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
        'deletion_pending_at',
    ];

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, AccountLink::class, 'player_uuid', 'uuid', 'uuid', 'user_uuid');
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

    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'employees', 'player_uuid', 'company_id', 'uuid', 'id')
            ->withPivot('role');
    }

    public function employeeAt(): HasManyThrough
    {
        return $this->hasManyThrough(Company::class, Employee::class, 'player_uuid', 'id', 'uuid', 'company_id')->where('role', '=', 'employee');
    }

    public function managerAt(): HasManyThrough
    {
        return $this->hasManyThrough(Company::class, Employee::class, 'player_uuid', 'id', 'uuid', 'company_id')->where('role', '=', 'manager');
    }

    public function amountOfCompanies(): int
    {
        return $this->companies()->count() + $this->managerAt()->count();
    }

    public function chatColors(): BelongsTo
    {
        return $this->belongsTo(PlayerChatColor::class, 'uuid', 'player_uuid');
    }

    public function prefixes(): BelongsTo
    {
        return $this->belongsTo(PlayerPrefix::class, 'uuid', 'player_uuid');
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
