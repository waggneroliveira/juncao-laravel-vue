<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAddress extends Model
{
    protected $table = 'client_addresses';
    
    protected $fillable = [
        'client_id',
        'nickname',
        'cep',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'reference',
        'instructions',
        'primary',
        'active',
        'delivery_region_id'
    ];
    
    protected $casts = [
        'primary' => 'boolean',
        'active' => 'boolean',
        'delivery_region_id' => 'integer'
    ];
    
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    
    public function deliveryRegion(): BelongsTo
    {
        return $this->belongsTo(DeliveryRegion::class);
    }

    public function getDeliveryFeeAttribute(): ?float
    {
        return $this->deliveryRegion?->delivery_fee;
    }

    public function isDeliveryAvailable(): bool
    {
        return $this->deliveryRegion !== null && $this->deliveryRegion->active;
    }
}