<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'status',
        'delivery_method',
        'payment_method',
        'subtotal',
        'discount',
        'cashback',
        'delivery_fee',
        'total',
        'notes',
        'estimated_time',
        'completed_at',
        'coupon_id',
        'delivery_location_id',
        'delivery_address',
    ];

    protected $casts = [
        'delivery_address' => 'json',
        'completed_at' => 'datetime',
    ];

    // Relacionamentos
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function deliveryLocation(): BelongsTo
    {
        return $this->belongsTo(DeliveryLocation::class);
    }
}
