<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table(
    key: 'player_uuid',
    keyType: 'string',
    incrementing: false,
)]
#[Fillable([
    'player_uuid',
    'server_name',
    'world',
    'x',
    'y',
    'z',
    'yaw',
    'pitch',
])]
class PlayerLocation extends Model
{
    //
}
