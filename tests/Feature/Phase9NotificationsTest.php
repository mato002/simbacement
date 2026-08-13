<?php

namespace Tests\Feature;

use App\Models\JobListing;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\Notifications\ContactMessageConfirmationNotification;
use App\Notifications\JobApplicationConfirmationNotification;
use App\Notifications\NewContactMessageNotification;
use App\Notifications\NewJobApplicationNotification;
use App\Notifications\NewQuoteRequestNotification;
use App\Notifications\QuoteRequestConfirmationNotification;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase9NotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SettingSeeder::class);
    }

    public function test_quote_submission_notifies_sales_and_customer(): void
    {
        Notification::fake();

        $category = ProductCategory::query()->create([
            'name' => 'Cement',
            'slug' => 'cement',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Simba Cement 32.5R',
            'slug' => 'simba-cement-32-5r',
            'unit' => 'bag',
            'is_active' => true,
            'is_published' => true,
        ]);

        $this->post(route('quote.store'), [
            'customer_type' => 'contractor',
            'name' => 'Jane Contractor',
            'phone' => '0711111111',
            'email' => 'jane@example.com',
            'product_id' => $product->id,
            'quantity' => 50,
            'unit' => 'bag',
        ])->assertRedirect();

        Notification::assertSentOnDemand(NewQuoteRequestNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'sales@simbacement.local'
                && $notification->quote->email === 'jane@example.com';
        });

        Notification::assertSentOnDemand(QuoteRequestConfirmationNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'jane@example.com';
        });
    }

    public function test_contact_submission_routes_by_department(): void
    {
        Notification::fake();

        $this->post(route('contact.store'), [
            'name' => 'Support Client',
            'email' => 'client@example.com',
            'subject' => 'Delivery issue',
            'message' => 'Need help with delivery timing.',
            'department' => 'support',
        ])->assertRedirect(route('contact'));

        Notification::assertSentOnDemand(NewContactMessageNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'support@simbacement.local'
                && $notification->message->department === 'support';
        });

        Notification::assertSentOnDemand(ContactMessageConfirmationNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'client@example.com';
        });
    }

    public function test_job_application_notifies_careers_and_applicant(): void
    {
        Notification::fake();
        Storage::fake('local');

        $job = JobListing::query()->create([
            'title' => 'Quality Analyst',
            'slug' => 'quality-analyst',
            'is_active' => true,
            'published_at' => now(),
        ]);

        $this->post(route('careers.apply', $job), [
            'full_name' => 'Alex Applicant',
            'email' => 'alex@example.com',
            'phone' => '0700555666',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('careers.show', $job));

        Notification::assertSentOnDemand(NewJobApplicationNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'careers@simbacement.local'
                && $notification->application->email === 'alex@example.com';
        });

        Notification::assertSentOnDemand(JobApplicationConfirmationNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'alex@example.com';
        });
    }

    public function test_missing_company_email_falls_back_to_mail_from(): void
    {
        Notification::fake();

        Setting::setValue('email_sales', null, 'company');
        config(['mail.from.address' => 'fallback@simbacement.local']);

        $category = ProductCategory::query()->create([
            'name' => 'Cement',
            'slug' => 'cement-fallback',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Simba Powercrete',
            'slug' => 'simba-powercrete',
            'unit' => 'bag',
            'is_active' => true,
            'is_published' => true,
        ]);

        $this->post(route('quote.store'), [
            'customer_type' => 'individual',
            'name' => 'Fallback Customer',
            'phone' => '0711222333',
            'email' => 'customer@example.com',
            'product_id' => $product->id,
            'quantity' => 10,
            'unit' => 'bag',
        ])->assertRedirect();

        Notification::assertSentOnDemand(NewQuoteRequestNotification::class, function ($notification, $channels, $notifiable) {
            return $notifiable->routes['mail'] === 'fallback@simbacement.local';
        });
    }
}
