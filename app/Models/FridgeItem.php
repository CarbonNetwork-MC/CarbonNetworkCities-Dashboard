<?php

namespace App\Models;

use App\Enums\FridgeCompartment;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'fridge_uuid',
    'compartment',
    'slot',
    'item_data',
])]
class FridgeItem extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'compartment' => FridgeCompartment::class,
            'slot' => 'integer',
        ];
    }

    /** @return BelongsTo<Fridge, $this> */
    public function fridge(): BelongsTo
    {
        return $this->belongsTo(Fridge::class, 'fridge_uuid', 'uuid');
    }
}
