<?php

namespace Database\Seeders;

use App\Enums\LocationType;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'type' => LocationType::Factory,
                'name' => 'Athi River Plant',
                'address' => 'Athi River, Machakos County',
                'county' => 'Machakos',
                'sort_order' => 1,
            ],
            [
                'type' => LocationType::Branch,
                'name' => 'Nakuru Operations',
                'address' => 'Nakuru County',
                'county' => 'Nakuru',
                'sort_order' => 2,
            ],
            [
                'type' => LocationType::HeadOffice,
                'name' => 'Head Office',
                'address' => null,
                'county' => null,
                'sort_order' => 0,
            ],
        ];

        foreach ($locations as $location) {
            Location::query()->updateOrCreate(
                ['slug' => Str::slug($location['name'])],
                [
                    'type' => $location['type'],
                    'name' => $location['name'],
                    'address' => $location['address'],
                    'county' => $location['county'],
                    'is_active' => true,
                    'sort_order' => $location['sort_order'],
                    'notes' => 'Confirm official address and contact details with the company before publishing.',
                ],
            );
        }
    }
}
