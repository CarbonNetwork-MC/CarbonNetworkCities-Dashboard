<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'open_tag',
    'close_tag',
    'is_bold',
    'hex',
])]
class ChatColor extends Model
{
    protected function casts(): array
    {
        return ['is_bold' => 'boolean'];
    }

    public function playerSelections(): HasMany
    {
        return $this->hasMany(PlayerChatColor::class, 'color_id');
    }
}
