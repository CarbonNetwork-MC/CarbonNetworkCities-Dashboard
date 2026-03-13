<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyStock extends Model
{
    protected $table = 'company_stock';
    protected $fillable = [
        'company_id',
        'item_id',
        'quantity',
        'preferred_stock_level',
        'warning_threshold',
        'critical_threshold',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(CompanyItem::class);
    }
}
