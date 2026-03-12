<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyTip extends Model
{
    protected $fillable = [
        'company_id',
        'amount',
        'customer_uuid',
        'employee_uuid',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'employee_uuid', 'uuid');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'customer_uuid', 'uuid');
    }

    public function salaryUpdates(): HasMany
    {
        return $this->hasMany(EmployeeSalaryUpdate::class, 'tip_id', 'id');
    }
}
