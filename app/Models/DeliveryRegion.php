<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class DeliveryRegion extends Model
{
    protected $table = 'delivery_regions';
    
    protected $fillable = [
        'name',
        'delivery_fee',
        'estimated_time_min',
        'estimated_time_max',
        'minimum_order_value',
        'active'
    ];
    
    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'estimated_time_min' => 'integer',
        'estimated_time_max' => 'integer',
        'minimum_order_value' => 'decimal:2',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function zipcodes(): HasMany
    {
        return $this->hasMany(DeliveryRegionZipcode::class);
    }
    
    public function clientAddresses(): HasMany
    {
        return $this->hasMany(ClientAddress::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}