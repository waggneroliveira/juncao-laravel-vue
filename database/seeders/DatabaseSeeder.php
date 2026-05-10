<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(1)->create();

        $this->call([
            SettingThemeSeeder::class,
            RoleSeeder::class,
            ProductSeeder::class,
            PermissionsSeeder::class,
            ModelHasRoleSeeder::class,
            SettingEmailSeeder::class,
            ClientSeeder::class,
            CompanySeeder::class,
        ]);
        
    }
}
