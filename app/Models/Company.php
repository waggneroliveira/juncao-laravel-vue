<?php

namespace App\Models;

use App\Models\CompanyOpeningHours;
use App\Services\CompanyStatusService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'phone',
        'whatsapp',
        'email',
        'description',
        'path_image',
        'path_image_banner',
        'timezone',
        'operation_mode',
    ];

    protected $appends = [
        'is_open',
        'status_label',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function openingHours(): HasMany
    {
        return $this->hasMany(CompanyOpeningHours::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getIsOpenAttribute(): bool
    {
        return app(CompanyStatusService::class)->isOpen($this);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_open ? 'Aberto' : 'Fechado';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAutomaticMode(): bool
    {
        return $this->operation_mode === 'automatic';
    }

    public function isForcedOpen(): bool
    {
        return $this->operation_mode === 'force_open';
    }

    public function isForcedClosed(): bool
    {
        return $this->operation_mode === 'force_closed';
    }
}