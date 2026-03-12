<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSalaryUpdate extends Model
{
    protected $fillable = [
        'salary_id',
        'player_uuid',
        'sale_id',
        'tip_id',
        'amount',
    ];

    public function salary(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalary::class, 'salary_id', 'id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(CompanySale::class, 'sale_id', 'id');
    }

    public function tip(): BelongsTo
    {
        return $this->belongsTo(CompanyTip::class, 'tip_id', 'id');
    }
}
