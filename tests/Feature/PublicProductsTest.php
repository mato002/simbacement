<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalogue_detail_and_compare_pages_work(): void
    {
        $category = ProductCategory::query()->create([
            'name' => 'Cement',
            'slug' => 'cement',
            'description' => 'Cement products',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $productA = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Simba Cement 32.5R',
            'slug' => 'simba-cement-325r',
            'short_description' => 'Reliable cement grade',
            'unit' => 'bag',
            'is_active' => true,
            'is_featured' => true,
            'is_comparable' => true,
            'published_at' => now(),
        ]);

        $productB = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Simba Cement 42.5N',
            'slug' => 'simba-cement-425n',
            'short_description' => 'Premium strength cement',
            'unit' => 'bag',
            'is_active' => true,
            'is_featured' => true,
            'is_comparable' => true,
            'published_at' => now(),
        ]);

        $productA->specifications()->create([
            'label' => 'Strength Class',
            'value' => '32.5R',
            'sort_order' => 1,
        ]);

        $productB->specifications()->create([
            'label' => 'Strength Class',
            'value' => '42.5N',
            'sort_order' => 1,
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Simba Cement 32.5R')
            ->assertSee('Simba Cement 42.5N');

        $this->get(route('products.index', ['category' => 'cement']))
            ->assertOk()
            ->assertSee('Cement');

        $this->get(route('products.show', $productA))
            ->assertOk()
            ->assertSee('Specifications')
            ->assertSee('Request Quote')
            ->assertSee('application/ld+json', false)
            ->assertSee('https://schema.org', false);

        $this->get(route('products.compare', [
            'products' => [$productA->slug, $productB->slug],
        ]))
            ->assertOk()
            ->assertSee('Strength Class')
            ->assertSee('32.5R')
            ->assertSee('42.5N');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Key products')
            ->assertSee('Simba Cement 32.5R');
    }
}
