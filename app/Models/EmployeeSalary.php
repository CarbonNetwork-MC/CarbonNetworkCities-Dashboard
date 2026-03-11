<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'company_id',
        'player_uuid',
        'year',
        'week',
        'amount',
        'status',
        'paid_at',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(EmployeeSalaryUpdate::class, 'salary_id', 'id');
    }
}
