<?php

namespace App\Models;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductOption extends Model
{
    use Notifiable, HasFactory, LogsActivity;
    
    protected $fillable = [
        'option_group_id',
        'name',
        'price',
        'is_default',
        'max_quantity',
        'description'
    ];

    public function group()
    {
        return $this->belongsTo(ProductOptionGroup::class, 'option_group_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        $activityLogService = new ActivityLogService($this);
        
        return LogOptions::defaults()
            ->logOnly($activityLogService->getLoggableAttributes());
    }
}
