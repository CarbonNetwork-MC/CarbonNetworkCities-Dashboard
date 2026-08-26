<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Spatie\Permission\Models\Permission as SpatiePermission;

/**
 * @property 
 */
#[Fillable([
    'name',
    'display_name',
    'description',
    'guard_name',
])]
#[Table(
    key: 'uuid',
    keyType: 'string',
    incrementing: false,
)]
class Permission extends SpatiePermission
{
    //
}
