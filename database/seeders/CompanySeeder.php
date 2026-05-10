<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyOpeningHour;
use App\Models\CompanyOpeningHours;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | COMPANY
        |--------------------------------------------------------------------------
        */

        $company = Company::create([
            'name'           => 'DeliFast',
            'slug'           => 'delifast',
            'phone'          => '(11) 99999-9999',
            'whatsapp'       => '11999999999',
            'email'          => 'contato@oxente.com',
            'description'    => 'Sitema de delivery.',
            'timezone'       => 'America/Sao_Paulo',
            'operation_mode' => 'automatic',
        ]);

        /*
        |--------------------------------------------------------------------------
        | OPENING HOURS
        |--------------------------------------------------------------------------
        |
        | 0 = Domingo
        | 1 = Segunda
        | 2 = Terça
        | 3 = Quarta
        | 4 = Quinta
        | 5 = Sexta
        | 6 = Sábado
        |
        |--------------------------------------------------------------------------
        */

        $hours = [

            // Segunda à Sexta
            // ['weekday' => 1, 'open_time' => '14:00:00', 'close_time' => '18:00:00'],
            ['weekday' => 2, 'open_time' => '16:00:00', 'close_time' => '21:00:00'],
            ['weekday' => 3, 'open_time' => '16:00:00', 'close_time' => '21:00:00'],
            ['weekday' => 4, 'open_time' => '16:00:00', 'close_time' => '21:00:00'],
            ['weekday' => 5, 'open_time' => '16:00:00', 'close_time' => '21:00:00'],

            // Sábado e Domingo
            ['weekday' => 6, 'open_time' => '14:00:00', 'close_time' => '23:00:00'],
            ['weekday' => 0, 'open_time' => '14:00:00', 'close_time' => '23:00:00'],
        ];

        foreach ($hours as $hour) {

            CompanyOpeningHours::create([
                'company_id' => $company->id,
                'weekday'    => $hour['weekday'],
                'open_time'  => $hour['open_time'],
                'close_time' => $hour['close_time'],
                'active'     => true,
            ]);
        }
    }
}