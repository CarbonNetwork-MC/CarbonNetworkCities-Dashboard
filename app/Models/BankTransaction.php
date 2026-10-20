<?php

namespace App\Models;

use App\Enums\BankTransactionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'personal_bank_account_id',
    'type',
    'amount',
    'balance_after',
    'idempotency_key',
    'description',
    'data',
])]
class BankTransaction extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => BankTransactionType::class,
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'data' => 'array',
        ];
    }

    /** @return BelongsTo<PersonalBankAccount, $this> */
    public function personalBankAccount(): BelongsTo
    {
        return $this->belongsTo(PersonalBankAccount::class);
    }
}
