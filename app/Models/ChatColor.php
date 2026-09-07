<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'open_tag',
    'close_tag',
    'is_bold',
    'hex'
])]
class ChatColor extends Model
{
    //
}
