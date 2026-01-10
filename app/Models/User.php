<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Language;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'onboarding_status',
        'onboarding_step',
        'selected_language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function player(): HasOneThrough
    {
        return $this->hasOneThrough(Player::class, AccountLink::class, 'user_uuid', 'uuid', 'uuid', 'player_uuid');
    }

    public function tokens(): HasOne
    {
        return $this->hasOne(AccountLinkToken::class, 'user_uuid', 'uuid');
    }

    public function playerHead()
    {
        return 'https://cravatar.eu/avatar/' . $this->player->uuid . '/64.png';
    }

    public function selectedLanguage(): HasOne
    {
        return $this->hasOne(Language::class, 'id', 'selected_language');
    }

    public function accountLink(): HasOne
    {
        return $this->hasOne(AccountLink::class, 'user_uuid', 'uuid');
    }
}
