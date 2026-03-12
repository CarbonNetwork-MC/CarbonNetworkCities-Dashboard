<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyBankaccount extends Model
{
    protected $fillable = [
        'company_id',
        'balance',
        'is_main',
        'currency',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    // Outgoing transactions
    public function outgoingTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'from_company_id');
    }

    // Incoming transactions  
    public function incomingTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'to_company_id');
    }

    // All transactions (both incoming and outgoing)
    public function allTransactions()
    {
        return BankTransaction::where('from_company_id', $this->id)
            ->orWhere('to_company_id', $this->id);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'currency', 'currency');
    }
}
