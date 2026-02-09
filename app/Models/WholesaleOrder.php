<?php

namespace App\Models;

use App\Models\Player;
use App\Models\Company;
use App\Models\WholesaleOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WholesaleOrder extends Model
{
    protected $fillable = [
        'company_id',
        'customer_id',
        'collected',
        'collected_by',
        'completed',
        'completed_by',
        'total',
    ];

    protected $casts = [
        'collected' => 'boolean',
        'completed' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WholesaleOrderItem::class, 'order_id');
    }

    public function amountOfItems(): int
    {
        return $this->items()->sum('amount');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'customer_id', 'uuid');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'completed_by', 'uuid');
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'collected_by', 'uuid');
    }
}
