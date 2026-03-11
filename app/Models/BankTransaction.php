<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    protected $fillable = [
        'from_company_id',
        'to_company_id',
        'from_company_name',
        'to_company_name',
        'from_personal_id',
        'to_personal_id',
        'from_player_name',
        'to_player_name',
        'amount',
        'currency',
        'description',
        'transaction_type',
        'player_uuid',
    ];
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function fromCompany()
    {
        return $this->belongsTo(CompanyBankaccount::class, 'from_company_id');
    }

    public function toCompany()
    {
        return $this->belongsTo(CompanyBankaccount::class, 'to_company_id');
    }

    public function fromPersonal()
    {
        return $this->belongsTo(PersonalBankaccount::class, 'from_personal_id');
    }

    public function toPersonal()
    {
        return $this->belongsTo(PersonalBankaccount::class, 'to_personal_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_uuid', 'uuid');
    }
}
