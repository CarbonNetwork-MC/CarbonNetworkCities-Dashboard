<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class CompanySale extends Model
{
    protected $fillable = [
        'company_id',
        'year',
        'week',
        'quantity',
        'total_revenue',
        'customer_uuid',
        'employee_uuid',
        'salary_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'customer_uuid', 'uuid');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'employee_uuid', 'uuid');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CompanySaleItem::class, 'company_sale_id', 'id');
    }

    public function salaryUpdate(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalaryUpdate::class, 'sale_id', 'id');
    }

    public function salary(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalary::class, 'salary_id', 'id');
    }
}
