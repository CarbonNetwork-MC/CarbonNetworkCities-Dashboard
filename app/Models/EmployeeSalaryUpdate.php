<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeSalaryUpdate extends Model
{
    protected $fillable = [
        'player_uuid',
        'sale_id',
        'amount',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(CompanySale::class, 'sale_id', 'id');
    }
}
