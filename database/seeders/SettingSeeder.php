<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'company', 'key' => 'legal_name', 'value' => 'Simba Cement', 'type' => 'string'],
            ['group' => 'company', 'key' => 'tagline', 'value' => 'Building Kenya. Building the Future.', 'type' => 'string'],
            ['group' => 'company', 'key' => 'short_description', 'value' => 'Official manufacturer of high-quality cement and building materials engineered for strength, durability and performance.', 'type' => 'string'],
            ['group' => 'company', 'key' => 'phone_sales', 'value' => null, 'type' => 'string'],
            ['group' => 'company', 'key' => 'phone_support', 'value' => null, 'type' => 'string'],
            ['group' => 'company', 'key' => 'email_sales', 'value' => 'sales@simbacement.local', 'type' => 'string'],
            ['group' => 'company', 'key' => 'email_support', 'value' => 'support@simbacement.local', 'type' => 'string'],
            ['group' => 'company', 'key' => 'email_careers', 'value' => 'careers@simbacement.local', 'type' => 'string'],
            ['group' => 'company', 'key' => 'address_head_office', 'value' => null, 'type' => 'string'],
            ['group' => 'social', 'key' => 'facebook', 'value' => null, 'type' => 'string'],
            ['group' => 'social', 'key' => 'linkedin', 'value' => null, 'type' => 'string'],
            ['group' => 'social', 'key' => 'instagram', 'value' => null, 'type' => 'string'],
            ['group' => 'social', 'key' => 'x', 'value' => null, 'type' => 'string'],
            ['group' => 'social', 'key' => 'youtube', 'value' => null, 'type' => 'string'],
            ['group' => 'seo', 'key' => 'default_title', 'value' => 'Simba Cement — Building Kenya. Building the Future.', 'type' => 'string'],
            ['group' => 'seo', 'key' => 'default_description', 'value' => 'Official Simba Cement manufacturer website for cement, steel and building materials across Kenya.', 'type' => 'string'],
            ['group' => 'stats', 'key' => 'years_experience', 'value' => null, 'type' => 'string'],
            ['group' => 'stats', 'key' => 'products_count', 'value' => null, 'type' => 'string'],
            ['group' => 'stats', 'key' => 'distribution_points', 'value' => null, 'type' => 'string'],
            ['group' => 'stats', 'key' => 'projects_served', 'value' => null, 'type' => 'string'],
            ['group' => 'site', 'key' => 'positioning', 'value' => 'official_manufacturer', 'type' => 'string'],
            ['group' => 'site', 'key' => 'commerce_mode', 'value' => 'quotes_only', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']],
            );
        }
    }
}
