<?php

namespace Tests\Feature;

use App\Enums\ProjectCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Solution;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolutionsProjectsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_and_admin_solutions_projects_flow(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Cement',
            'slug' => 'cement',
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Simba Cement 42.5N',
            'slug' => 'simba-cement-425n',
            'unit' => 'bag',
            'is_active' => true,
            'published_at' => now(),
        ]);

        $solution = Solution::query()->create([
            'name' => 'Residential Construction',
            'slug' => 'residential-construction',
            'headline' => 'Building your home?',
            'summary' => 'Cement solutions for homes',
            'content' => 'Detailed residential guidance',
            'highlights' => ['Foundations', 'Slabs'],
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $solution->products()->attach($product->id, ['sort_order' => 1]);

        $project = Project::query()->create([
            'title' => 'Nairobi Residential Estate',
            'slug' => 'nairobi-residential-estate',
            'location' => 'Nairobi',
            'client' => 'Developer',
            'year' => 2024,
            'category' => ProjectCategory::Residential,
            'summary' => 'Residential estate project',
            'overview' => 'Project overview',
            'challenge' => 'Tight programme',
            'solution' => 'Specified Simba Cement grades',
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now(),
        ]);
        $project->products()->attach($product->id);

        $this->get(route('solutions.index'))->assertOk()->assertSee('Residential Construction');
        $this->get(route('solutions.show', $solution))
            ->assertOk()
            ->assertSee('Recommended products')
            ->assertSee('Simba Cement 42.5N');

        $this->get(route('projects.index'))->assertOk()->assertSee('Nairobi Residential Estate');
        $this->get(route('projects.index', ['category' => 'residential']))
            ->assertOk()
            ->assertSee('Residential');
        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Challenge')
            ->assertSee('Products Used');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Featured projects')
            ->assertSee('Residential Construction');

        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('super-admin');

        $this->actingAs($admin)
            ->get(route('admin.solutions.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.projects.index'))
            ->assertOk();
    }
}
