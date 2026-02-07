<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Fridge;

class Plot extends Model
{
    protected $fillable = [
        'plot_id',
        'name',
        'description',
        'city',
        'country_id',
        'world_id',
        'company_id',
        'owner_uuid',
        'min_x',
        'min_y',
        'min_z',
        'max_x',
        'max_y',
        'max_z',
        'for_sale',
        'price',
        'type',
        'tp_x',
        'tp_y',
        'tp_z',
        'tp_yaw',
        'tp_pitch',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'owner_uuid', 'uuid');
    }

    public function members(): HasMany
    {
        return $this->hasMany(PlotMember::class, 'plot_id', 'plot_id');
    }

    public function fridges(): HasMany
    {
        return $this->hasMany(Fridge::class, 'plot_id', 'plot_id');
    }
}
