<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use Notifiable, HasFactory, LogsActivity;
    
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'old_price',
        'cashback',
        'product_type',
        'cuisine_type',
        'is_combo',
        'featured',
        'highlights',
        'order',
        'tags',
        'specifications',
        'path_image',
        'active',
        'sorting'
    ];

    protected $casts = [
        'tags' => 'array',
        'specifications' => 'array',
        'is_combo' => 'boolean'        
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function optionGroups()
    {
        // Busca grupos que pertencem diretamente ao produto OU aos comboItems do produto
        return $this->hasMany(ProductOptionGroup::class, 'product_id')
            ->orWhereIn('combo_item_id', $this->comboItems->pluck('id'));
    }

    public function comboItems()
    {
        return $this->hasMany(ComboItem::class);
    }

    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }
    public function scopeSorting($query)
    {
        return $query->orderBy('sorting', 'asc');
    }
    public function getActivitylogOptions(): LogOptions
    {
        $activityLogService = new ActivityLogService($this);
        
        return LogOptions::defaults()
            ->logOnly($activityLogService->getLoggableAttributes());
    }
}
