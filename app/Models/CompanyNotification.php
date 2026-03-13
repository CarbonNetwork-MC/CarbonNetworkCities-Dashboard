<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyNotification extends Model
{
    protected $fillable = [
        'company_id',
        'item_id',
        'order_id',
        'type',
        'level',
        'message',
        'is_read',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(CompanyItem::class, 'item_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(WholesaleOrder::class, 'order_id');
    }
}
