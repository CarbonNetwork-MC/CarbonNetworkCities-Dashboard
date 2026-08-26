<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Spatie\Permission\Models\Role as SpatieRole;

#[Table(
    key: 'uuid',
    keyType: 'string',
    incrementing: false,
)]
class Role extends SpatieRole
{
    //
}
