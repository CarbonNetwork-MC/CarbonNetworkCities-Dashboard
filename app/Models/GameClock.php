<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'real_anchor_at',
    'game_anchor_at',
    'time_scale',
    'timezone',
    'version',
])]
class GameClock extends Model
{
    protected $table = 'game_clock';

    public $incrementing = false;

    /** @var string */
    protected $keyType = 'int';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'real_anchor_at' => 'immutable_datetime',
            'game_anchor_at' => 'immutable_datetime',
            'time_scale' => 'decimal:6',
            'version' => 'integer',
        ];
    }
}
