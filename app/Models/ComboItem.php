<?php

namespace App\Models;

use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ComboItem extends Model
{
    use Notifiable, HasFactory, LogsActivity;
    
    protected $fillable = [
        'product_id',
        'name',
        'item_key',
        'quantity',
        'required'
    ];

    public function optionGroups()
    {
        return $this->hasMany(ProductOptionGroup::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        $activityLogService = new ActivityLogService($this);
        
        return LogOptions::defaults()
            ->logOnly($activityLogService->getLoggableAttributes());
    }
}
