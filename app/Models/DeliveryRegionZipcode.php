<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryRegionZipcode extends Model
{
    protected $table = 'delivery_region_zipcodes';
    
    protected $fillable = [
        'delivery_region_id',
        'zipcode_prefix'
    ];
    
    protected $casts = [
        'delivery_region_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function deliveryRegion(): BelongsTo
    {
        return $this->belongsTo(DeliveryRegion::class);
    }
}