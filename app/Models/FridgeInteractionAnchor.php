<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'fridge_uuid',
    'world',
    'x',
    'y',
    'z',
])]
class FridgeInteractionAnchor extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'x' => 'integer',
            'y' => 'integer',
            'z' => 'integer',
        ];
    }

    /** @return BelongsTo<Fridge, $this> */
    public function fridge(): BelongsTo
    {
        return $this->belongsTo(Fridge::class, 'fridge_uuid', 'uuid');
    }
}
