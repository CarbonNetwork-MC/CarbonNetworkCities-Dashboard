<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    protected $fillable = [
        'name',
        'world_id',
        'coc_type',
        'coc_number',
        'owner_uuid',
    ];

    public function owner(): HasOne
    {
        return $this->hasOne(Player::class, 'uuid', 'owner_uuid');
    }

    public function cocType(): HasOne
    {
        return $this->hasOne(CocType::class, 'name', 'coc_type');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(CompanyBankaccount::class, 'company_id', 'id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_id', 'id');
    }

    public function plots(): HasMany
    {
        return $this->hasMany(Plot::class, 'company_id', 'id');
    }

    public function pinConsoles(): HasMany
    {
        return $this->hasMany(PinConsole::class, 'company_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CompanyItem::class, 'company_id', 'id');
    }
}
