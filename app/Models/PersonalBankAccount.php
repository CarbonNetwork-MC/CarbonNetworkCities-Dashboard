<?php

namespace App\Models;

use App\Enums\BankAccountType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'player_uuid',
    'type',
    'currency',
    'balance',
])]
class PersonalBankAccount extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => BankAccountType::class,
            'balance' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<Player, $this> */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }

    /** @return HasMany<BankTransaction, $this> */
    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }
}
