<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryLocation extends Model
{
    protected $fillable = [
        'name',
        'type',
        'address',
        'neighborhood',
        'city',
        'state',
        'postal_code',
        'latitude',
        'longitude',
        'description',
        'phone',
        'working_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relacionamentos
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
