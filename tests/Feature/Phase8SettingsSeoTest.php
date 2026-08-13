<?php

namespace Tests\Feature;

use App\Enums\JobApplicationStatus;
use App\Enums\LocationType;
use App\Enums\MessageStatus;
use App\Enums\QuoteStatus;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\Location;
use App\Models\QuoteRequest;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase8SettingsSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SettingSeeder::class);
    }

    public function test_admin_can_update_settings_and_manage_locations(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super-admin');

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Site Settings');

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'company' => [
                    'legal_name' => 'Simba Cement Ltd',
                    'email_sales' => 'sales@example.com',
                    'short_description' => 'Official manufacturer.',
                ],
                'seo' => [
                    'default_title' => 'Simba Cement SEO Title',
                    'default_description' => 'SEO description for Simba Cement.',
                ],
                'stats' => [
                    'years_experience' => '25+',
                    'products_count' => '40+',
                ],
                'site' => [
                    'positioning' => 'official_manufacturer',
                    'commerce_mode' => 'quotes_only',
                ],
            ])
            ->assertRedirect();

        $this->assertSame('Simba Cement Ltd', Setting::getValue('legal_name', null, 'company'));
        $this->assertSame('25+', Setting::getValue('years_experience', null, 'stats'));

        $this->actingAs($admin)
            ->post(route('admin.locations.store'), [
                'type' => LocationType::Branch->value,
                'name' => 'Nairobi Branch',
                'county' => 'Nairobi',
                'phone' => '0700000000',
                'email' => 'nairobi@example.com',
                'is_active' => 1,
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.locations.index'));

        $location = Location::query()->where('slug', 'nairobi-branch')->first();
        $this->assertNotNull($location);

        $this->actingAs($admin)
            ->get(route('admin.locations.index'))
            ->assertOk()
            ->assertSee('Nairobi Branch');
    }

    public function test_sitemap_and_robots_are_public(): void
    {
        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('home'), false)
            ->assertSee(route('products.index'), false);

        $this->get(route('robots'))
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee(url('/sitemap.xml'));
    }

    public function test_dashboard_shows_inbox_alerts(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super-admin');

        QuoteRequest::query()->create([
            'reference' => 'QT-2026-000001',
            'customer_type' => 'contractor',
            'name' => 'Alert Quote',
            'phone' => '0711111111',
            'email' => 'quote@example.com',
            'status' => QuoteStatus::New,
        ]);

        ContactMessage::query()->create([
            'name' => 'Alert Message',
            'email' => 'message@example.com',
            'subject' => 'Need help',
            'message' => 'Hello',
            'status' => MessageStatus::New,
        ]);

        $job = JobListing::query()->create([
            'title' => 'Plant Engineer',
            'slug' => 'plant-engineer',
            'is_active' => true,
            'published_at' => now(),
        ]);

        JobApplication::query()->create([
            'job_listing_id' => $job->id,
            'full_name' => 'Alert Applicant',
            'email' => 'applicant@example.com',
            'phone' => '0722222222',
            'position' => 'Engineer',
            'cv_path' => 'cvs/alert-applicant.pdf',
            'status' => JobApplicationStatus::Received,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('1 new quotation request')
            ->assertSee('1 new contact message')
            ->assertSee('1 new job application')
            ->assertSee('QT-2026-000001')
            ->assertSee('Need help')
            ->assertSee('Alert Applicant');
    }

    public function test_public_footer_uses_company_settings(): void
    {
        Setting::setValue('legal_name', 'Simba Cement Public', 'company');
        Setting::setValue('email_sales', 'hello@simba.test', 'company');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Simba Cement Public')
            ->assertSee('hello@simba.test');
    }
}
