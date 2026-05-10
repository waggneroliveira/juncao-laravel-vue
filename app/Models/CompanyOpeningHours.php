<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyOpeningHours extends Model
{
    protected $table = 'company_opening_hours';

    protected $fillable = [
        'company_id',
        'weekday',
        'open_time',
        'close_time',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getWeekdayLabelAttribute(): string
    {
        return match ($this->weekday) {
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
            default => 'Dia inválido',
        };
    }

    public function getFormattedOpenTimeAttribute(): string
    {
        return substr($this->open_time, 0, 5);
    }

    public function getFormattedCloseTimeAttribute(): string
    {
        return substr($this->close_time, 0, 5);
    }

    public function getFormattedPeriodAttribute(): string
    {
        return "{$this->formatted_open_time} às {$this->formatted_close_time}";
    }
}