<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Database\Seeders\CorporatePageSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorporatePagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(CorporatePageSeeder::class);
    }

    public function test_corporate_pages_render_and_are_editable(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('About Simba Cement')
            ->assertSee('Our Journey');

        $this->get(route('manufacturing'))
            ->assertOk()
            ->assertSee('Our Manufacturing Process')
            ->assertSee('Raw Materials');

        $this->get(route('quality'))
            ->assertOk()
            ->assertSee('Quality at Every Stage')
            ->assertSee('Downloadable Documents');

        $this->get(route('sustainability'))
            ->assertOk()
            ->assertSee('Sustainable Manufacturing')
            ->assertSee('Employee safety');

        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super-admin');

        $page = Page::query()->where('slug', 'about')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.pages.index'))
            ->assertOk()
            ->assertSee('About Us');

        $this->actingAs($admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'About Us',
                'slug' => 'about',
                'eyebrow' => 'Company',
                'headline' => 'About Simba Cement Updated',
                'summary' => 'Updated summary',
                'is_published' => 1,
                'sections' => [
                    [
                        'type' => 'text',
                        'title' => 'Our Story',
                        'body' => 'Updated story body',
                        'items' => '',
                    ],
                ],
            ])
            ->assertRedirect();

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('About Simba Cement Updated')
            ->assertSee('Updated story body');
    }
}
