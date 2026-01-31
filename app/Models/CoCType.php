<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoCType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function itemGroups(): HasMany
    {
        return $this->hasMany(ItemGroup::class, 'coc_type', 'name');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class, 'coc_type', 'name');
    }
}
