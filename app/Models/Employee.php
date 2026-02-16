<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'player_uuid',
        'company_id',
        'is_paid',
        'salary_percentage',
        'role',
    ];
    protected $casts = [
        'is_paid' => 'boolean',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
