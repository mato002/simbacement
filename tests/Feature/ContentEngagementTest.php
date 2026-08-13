<?php

namespace Tests\Feature;

use App\Enums\JobApplicationStatus;
use App\Enums\MessageStatus;
use App\Enums\NewsCategory;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\NewsArticle;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentEngagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_news_contact_and_careers_flows(): void
    {
        Storage::fake('local');

        $article = NewsArticle::query()->create([
            'title' => 'Quality First Update',
            'slug' => 'quality-first-update',
            'category' => NewsCategory::Update,
            'excerpt' => 'Quality remains central.',
            'body' => 'Full article body.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $job = JobListing::query()->create([
            'title' => 'Production Engineer',
            'slug' => 'production-engineer',
            'location' => 'Athi River',
            'department' => 'Manufacturing',
            'employment_type' => 'full-time',
            'summary' => 'Join manufacturing.',
            'requirements' => 'Engineering degree',
            'responsibilities' => 'Support production',
            'is_active' => true,
            'published_at' => now(),
        ]);

        $this->get(route('news.index'))->assertOk()->assertSee('Quality First Update');
        $this->get(route('news.show', $article))
            ->assertOk()
            ->assertSee('https://schema.org', false);

        $this->get(route('contact'))->assertOk()->assertSee('Contact Us');
        $this->post(route('contact.store'), [
            'name' => 'Jane Client',
            'email' => 'jane@example.com',
            'phone' => '0700111222',
            'county' => 'Nairobi',
            'subject' => 'Product enquiry',
            'message' => 'Need cement for a project.',
            'department' => 'sales',
        ])->assertRedirect(route('contact'));

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'jane@example.com',
            'status' => MessageStatus::New->value,
        ]);

        $this->get(route('careers.index'))->assertOk()->assertSee('Production Engineer');
        $this->get(route('careers.show', $job))->assertOk()->assertSee('Apply Now');

        $this->post(route('careers.apply', $job), [
            'full_name' => 'John Applicant',
            'email' => 'john@example.com',
            'phone' => '0700333444',
            'cover_letter' => 'I am interested.',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('careers.show', $job));

        $application = JobApplication::query()->first();
        $this->assertNotNull($application);
        $this->assertSame(JobApplicationStatus::Received, $application->status);
        Storage::disk('local')->assertExists($application->cv_path);

        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super-admin');

        $this->actingAs($admin)->get(route('admin.news.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.messages.index'))->assertOk()->assertSee('Jane Client');
        $this->actingAs($admin)->get(route('admin.applications.show', $application))->assertOk();
        $this->actingAs($admin)->get(route('admin.applications.cv', $application))->assertOk();
    }
}
