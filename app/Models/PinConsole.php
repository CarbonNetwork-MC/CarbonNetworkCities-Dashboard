<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PinConsole extends Model
{
    protected $fillable = [
        'company_id',
        'account_id',
        'x',
        'y',
        'z',
        'city',
        'country_id',
        'world_id',
        'is_active',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(CompanyBankaccount::class, 'account_id', 'id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
