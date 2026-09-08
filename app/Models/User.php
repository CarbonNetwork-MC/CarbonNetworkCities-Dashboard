<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $profile_photo_path
 * @property string|null $locale
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'email',
    'password',
    'profile_photo_path',
    'locale',
])]
#[Hidden(['password', 'remember_token'])]
#[Table(
    key: 'uuid',
    keyType: 'string',
    incrementing: false,
)]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

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

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    /**
     * Get the user's profile photo URL, or a default avatar if not set.
     */
    public function profilePhotoUrl(): string
    {
        $profilePhotoPath = $this->profile_photo_path;
        $backgroundColor = '16a34a'; // Tailwind CSS green-600
        $textColor = 'ffffff'; // White

        return $profilePhotoPath
            ? Storage::url($profilePhotoPath)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background='.$backgroundColor.'&color='.$textColor;
    }

    /**
     * Get the selected locale for the user, or the default app locale if not set.
     *
     * @return
     */
    // public function selectedLanguage():
}
