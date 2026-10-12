<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'player_uuid',
    'username',
    'state',
    'language_id',
    'country_id'
])]
class OnboardingPlayer extends Model
{
    public function country(): BelongsTo {
        return $this->belongsTo(Country::class, 'country_id');
    }
    
    public function language(): BelongsTo {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
