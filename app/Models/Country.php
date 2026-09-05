<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'iso',
    'flag_code',
    'headdb_id'
])]
class Country extends Model
{
    //
}
