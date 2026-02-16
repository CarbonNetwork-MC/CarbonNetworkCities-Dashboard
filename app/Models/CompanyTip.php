<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyTip extends Model
{
    protected $fillable = [
        'company_id',
        'amount',
        'customer_uuid',
        'employee_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'customer_uuid', 'uuid');
    }
}
