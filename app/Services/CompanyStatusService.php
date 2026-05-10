<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Company;

class CompanyStatusService
{
    public function isOpen(Company $company): bool
    {

        if ($company->operation_mode === 'force_open') {
            return true;
        }

        if ($company->operation_mode === 'force_closed') {
            return false;
        }

        $now = Carbon::now($company->timezone);

        $weekday = $now->dayOfWeek;

        $currentTime = $now->format('H:i:s');

        $hours = $company->openingHours()
            ->where('weekday', $weekday)
            ->where('active', true)
            ->get();

        foreach ($hours as $hour) {

            if (
                $currentTime >= $hour->open_time &&
                $currentTime <= $hour->close_time
            ) {
                return true;
            }
        }

        return false;
    }

    public function getPublicData(): array
    {
        $company = Company::with([
            'openingHours' => function ($query) {
                $query
                    ->where('active', true)
                    ->orderBy('weekday')
                    ->orderBy('open_time');
            }
        ])->first();

        if (!$company) {
            return [];
        }

        return [
            'id' => $company->id,
            'name' => $company->name,
            'slug' => $company->slug,
            'phone' => $company->phone,
            'whatsapp' => $company->whatsapp,
            'email' => $company->email,

            'description' => $company->description,

            'logo' => $company->logo,
            'banner' => $company->banner,

            'is_open' => $company->is_open,
            'status_label' => $company->status_label,

            'opening_hours' => $company->openingHours->map(function ($hour) {

                return [
                    'weekday' => $hour->weekday,
                    'weekday_label' => $hour->weekday_label,
                    'formatted_period' => $hour->formatted_period,
                ];
            })->values(),
        ];
    }
}