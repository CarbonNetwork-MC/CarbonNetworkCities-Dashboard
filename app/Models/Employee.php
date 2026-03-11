<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function salaries(): HasMany
    {
        return $this->hasMany(EmployeeSalary::class, 'player_uuid', 'player_uuid');
    }

    public function latestSalary(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalary::class, 'player_uuid', 'player_uuid')->latestOfMany();
    }

    public function salaryUpdates(): HasMany
    {
        return $this->hasMany(EmployeeSalaryUpdate::class, 'player_uuid', 'player_uuid');
    }
}
