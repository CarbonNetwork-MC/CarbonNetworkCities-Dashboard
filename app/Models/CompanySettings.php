<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySettings extends Model
{
    protected $fillable = [
        'company_id',
        'salary_scheme',
        'tip_schema',
        'default_salary_percentage',
    ];

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
