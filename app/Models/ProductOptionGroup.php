<?php

namespace App\Models;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductOptionGroup extends Model
{
    use Notifiable, HasFactory, LogsActivity;
    
    protected $fillable = [
        'product_id',
        'combo_item_id',
        'name',
        'type',
        'required',
        'max_selections'
    ];

    public function options()
    {
        return $this->hasMany(ProductOption::class, 'option_group_id');
    }

    public function comboItem()
    {
        return $this->belongsTo(ComboItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        $activityLogService = new ActivityLogService($this);
        
        return LogOptions::defaults()
            ->logOnly($activityLogService->getLoggableAttributes());
    }
}
