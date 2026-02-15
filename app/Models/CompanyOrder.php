<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyOrder extends Model
{
    protected $fillable = [
        'company_id',
        'order_id',
        'completed',
        'completed_by',
        'stock_updated',
    ];

    public function company(): BelongsTo {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function order(): BelongsTo {
        return $this->belongsTo(WholesaleOrder::class, 'order_id');
    }

    public function completedBy(): BelongsTo {
        return $this->belongsTo(Player::class, 'completed_by', 'uuid');
    }
}
