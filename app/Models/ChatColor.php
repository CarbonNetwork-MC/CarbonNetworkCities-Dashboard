<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatColor extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_bold',
        'hex',
    ];
}
