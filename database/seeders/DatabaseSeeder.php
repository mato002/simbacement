<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            SettingSeeder::class,
            LocationSeeder::class,
            ProductSeeder::class,
            SolutionSeeder::class,
            ProjectSeeder::class,
            ContentSeeder::class,
            CorporatePageSeeder::class,
            SampleQuoteSeeder::class,
        ]);
    }
}
