<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\QuoteRequest;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPhase3Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_manage_products_media_and_quotes(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@test.local',
            'is_active' => true,
        ]);
        $admin->assignRole('super-admin');

        $category = ProductCategory::query()->create([
            'name' => 'Cement',
            'slug' => 'cement',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Simba Cement 32.5R',
                'unit' => 'bag',
                'is_active' => 1,
                'is_published' => 1,
                'is_featured' => 1,
                'is_comparable' => 1,
                'specs' => [
                    ['label' => 'Strength', 'value' => '32.5R'],
                ],
                'primary_image' => UploadedFile::fake()->image('cement.jpg'),
            ])
            ->assertRedirect();

        $product = Product::query()->first();
        $this->assertNotNull($product);
        $this->assertSame('Simba Cement 32.5R', $product->name);
        $this->assertTrue($product->images()->exists());

        $this->actingAs($admin)
            ->post(route('admin.media.store'), [
                'files' => [UploadedFile::fake()->image('banner.jpg')],
                'folder' => 'banners',
            ])
            ->assertRedirect();

        $this->post(route('quote.store'), [
            'customer_type' => 'contractor',
            'name' => 'Jane Contractor',
            'phone' => '0711111111',
            'email' => 'jane@example.com',
            'product_id' => $product->id,
            'quantity' => 50,
            'unit' => 'bag',
        ])->assertRedirect();

        $quote = QuoteRequest::query()->first();
        $this->assertNotNull($quote);

        $this->actingAs($admin)
            ->get(route('admin.quotes.show', $quote))
            ->assertOk()
            ->assertSee($quote->reference);

        $this->actingAs($admin)
            ->patch(route('admin.quotes.update', $quote), [
                'status' => 'under_review',
                'assigned_to' => $admin->id,
                'admin_notes' => 'Calling customer today',
            ])
            ->assertRedirect();

        $this->assertSame('under_review', $quote->fresh()->status->value);
    }
}
