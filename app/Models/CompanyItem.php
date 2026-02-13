<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyItem extends Model
{
    protected $fillable = [
        'company_id',
        'item_id',
        'sellable',
        'price',
        'base_price'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(CompanyStock::class, 'item_id', 'id');
    }
  
    public function wholesaleItem()
    {
        return $this->hasOne(WholesaleItem::class, 'item_id', 'item_id');
    }
}
