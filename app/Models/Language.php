<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'iso',
    'locale',
    'headdb_id'
])]
class Language extends Model
{
    //
}
