<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanySaleItem extends Model
{
    protected $fillable = [
        'company_sale_id',
        'item_id',
        'quantity',
        'price',
    ];

    public function companySale(): BelongsTo
    {
        return $this->belongsTo(CompanySale::class, 'company_sale_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(CompanyItem::class, 'item_id', 'id');
    }
}
