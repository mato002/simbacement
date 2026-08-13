<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSpecification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cement = ProductCategory::query()->updateOrCreate(
            ['slug' => 'cement'],
            [
                'name' => 'Cement',
                'description' => 'High-performance cement grades for residential, commercial and infrastructure construction.',
                'is_active' => true,
                'sort_order' => 1,
                'seo_title' => 'Cement Products | Simba Cement',
                'meta_description' => 'Explore Simba Cement grades including 32.5R and 42.5N.',
            ]
        );

        $steel = ProductCategory::query()->updateOrCreate(
            ['slug' => 'steel'],
            [
                'name' => 'Steel',
                'description' => 'Reinforcement steel products for structural strength and durability.',
                'is_active' => true,
                'sort_order' => 2,
                'seo_title' => 'Steel Products | Simba Cement',
                'meta_description' => 'Simba Cement steel products including D8, D10, D12 and D16.',
            ]
        );

        $materials = ProductCategory::query()->updateOrCreate(
            ['slug' => 'building-materials'],
            [
                'name' => 'Building Materials',
                'description' => 'Complementary building materials for construction and distribution partners.',
                'is_active' => true,
                'sort_order' => 3,
                'seo_title' => 'Building Materials | Simba Cement',
                'meta_description' => 'Binding wire, hoop iron, paving blocks and related building materials.',
            ]
        );

        $products = [
            [
                'category_id' => $cement->id,
                'name' => 'Simba Cement 32.5R',
                'sku' => 'SC-CEM-325R',
                'tagline' => 'Reliable cement for everyday construction strength.',
                'unit' => 'bag',
                'short_description' => 'A versatile cement grade suitable for general construction applications.',
                'overview' => 'Simba Cement 32.5R is engineered for dependable performance across foundations, masonry and general structural works.',
                'applications' => "Foundations\nMasonry\nPlastering\nSlabs\nGeneral construction",
                'key_benefits' => "Consistent quality\nGood workability\nSuitable for a wide range of residential and light commercial uses",
                'packaging' => 'Available in standard bags. Bulk packaging options subject to confirmation.',
                'quality_standards' => 'Manufactured under controlled quality processes. Publish only company-approved standards and certificates.',
                'is_featured' => true,
                'sort_order' => 1,
                'specs' => [
                    ['label' => 'Strength Class', 'value' => '32.5R'],
                    ['label' => 'Recommended For', 'value' => 'General construction'],
                    ['label' => 'Packaging', 'value' => 'Bags'],
                ],
            ],
            [
                'category_id' => $cement->id,
                'name' => 'Simba Cement 42.5N',
                'sku' => 'SC-CEM-425N',
                'tagline' => 'Premium-strength cement for demanding applications.',
                'unit' => 'bag',
                'short_description' => 'Higher-strength cement for structural and performance-critical projects.',
                'overview' => 'Simba Cement 42.5N is designed for projects that require enhanced strength and durability.',
                'applications' => "Columns\nBeams\nHigh-rise structures\nInfrastructure works\nCommercial buildings",
                'key_benefits' => "Higher compressive strength\nSuitable for demanding structural works\nEngineered for consistent performance",
                'packaging' => 'Available in standard bags. Bulk packaging options subject to confirmation.',
                'quality_standards' => 'Manufactured under controlled quality processes. Publish only company-approved standards and certificates.',
                'is_featured' => true,
                'sort_order' => 2,
                'specs' => [
                    ['label' => 'Strength Class', 'value' => '42.5N'],
                    ['label' => 'Recommended For', 'value' => 'Structural & infrastructure'],
                    ['label' => 'Packaging', 'value' => 'Bags'],
                ],
            ],
            [
                'category_id' => $steel->id,
                'name' => 'D8',
                'sku' => 'SC-STL-D8',
                'tagline' => 'Reinforcement steel for light structural applications.',
                'unit' => 'ton',
                'short_description' => 'D8 reinforcement bars for light construction requirements.',
                'overview' => 'D8 steel reinforcement suitable for light structural and residential applications.',
                'applications' => "Light reinforcement\nResidential works\nSecondary structural elements",
                'key_benefits' => "Reliable reinforcement\nSuitable for distributor and contractor supply",
                'packaging' => 'Supplied according to standard steel packaging practices.',
                'quality_standards' => 'Confirm approved steel standards with the company before publishing.',
                'is_featured' => false,
                'sort_order' => 1,
                'specs' => [
                    ['label' => 'Diameter', 'value' => '8mm'],
                    ['label' => 'Category', 'value' => 'Reinforcement steel'],
                ],
            ],
            [
                'category_id' => $steel->id,
                'name' => 'D10',
                'sku' => 'SC-STL-D10',
                'tagline' => 'Versatile reinforcement for general structural use.',
                'unit' => 'ton',
                'short_description' => 'D10 reinforcement bars commonly used across construction projects.',
                'overview' => 'D10 steel reinforcement for general structural applications.',
                'applications' => "Slabs\nColumns\nBeams\nGeneral reinforcement",
                'key_benefits' => "Widely used diameter\nStrong structural performance",
                'packaging' => 'Supplied according to standard steel packaging practices.',
                'quality_standards' => 'Confirm approved steel standards with the company before publishing.',
                'is_featured' => true,
                'sort_order' => 2,
                'specs' => [
                    ['label' => 'Diameter', 'value' => '10mm'],
                    ['label' => 'Category', 'value' => 'Reinforcement steel'],
                ],
            ],
            [
                'category_id' => $steel->id,
                'name' => 'D12',
                'sku' => 'SC-STL-D12',
                'tagline' => 'Structural reinforcement for heavier load requirements.',
                'unit' => 'ton',
                'short_description' => 'D12 reinforcement bars for medium to heavy structural works.',
                'overview' => 'D12 steel reinforcement for stronger structural applications.',
                'applications' => "Columns\nBeams\nFoundations\nCommercial structures",
                'key_benefits' => "Higher load capacity\nSuitable for commercial builds",
                'packaging' => 'Supplied according to standard steel packaging practices.',
                'quality_standards' => 'Confirm approved steel standards with the company before publishing.',
                'is_featured' => false,
                'sort_order' => 3,
                'specs' => [
                    ['label' => 'Diameter', 'value' => '12mm'],
                    ['label' => 'Category', 'value' => 'Reinforcement steel'],
                ],
            ],
            [
                'category_id' => $steel->id,
                'name' => 'D16',
                'sku' => 'SC-STL-D16',
                'tagline' => 'Heavy-duty reinforcement for major structural works.',
                'unit' => 'ton',
                'short_description' => 'D16 reinforcement bars for major and infrastructure-related structures.',
                'overview' => 'D16 steel reinforcement for demanding structural projects.',
                'applications' => "Heavy columns\nInfrastructure\nIndustrial structures",
                'key_benefits' => "High structural capacity\nIdeal for large-scale works",
                'packaging' => 'Supplied according to standard steel packaging practices.',
                'quality_standards' => 'Confirm approved steel standards with the company before publishing.',
                'is_featured' => false,
                'sort_order' => 4,
                'specs' => [
                    ['label' => 'Diameter', 'value' => '16mm'],
                    ['label' => 'Category', 'value' => 'Reinforcement steel'],
                ],
            ],
            [
                'category_id' => $materials->id,
                'name' => 'Binding Wire',
                'sku' => 'SC-BM-BW',
                'tagline' => 'Essential binding wire for reinforcement works.',
                'unit' => 'roll',
                'short_description' => 'Binding wire for tying reinforcement and general site use.',
                'overview' => 'Binding wire used across construction sites for reinforcement tying and related applications.',
                'applications' => "Rebar tying\nSite fabrication\nGeneral construction support",
                'key_benefits' => "Practical site essential\nAvailable for contractors and hardware partners",
                'packaging' => 'Standard rolls. Confirm pack sizes with sales.',
                'quality_standards' => 'Confirm product specifications with the company before publishing.',
                'is_featured' => false,
                'sort_order' => 1,
                'specs' => [
                    ['label' => 'Category', 'value' => 'Building materials'],
                    ['label' => 'Unit', 'value' => 'Roll'],
                ],
            ],
            [
                'category_id' => $materials->id,
                'name' => 'Hoop Iron',
                'sku' => 'SC-BM-HI',
                'tagline' => 'Hoop iron for masonry and structural tying needs.',
                'unit' => 'roll',
                'short_description' => 'Hoop iron used in masonry reinforcement and related construction applications.',
                'overview' => 'Hoop iron supplied for construction and distribution channels.',
                'applications' => "Masonry reinforcement\nWall tying\nGeneral construction",
                'key_benefits' => "Useful site material\nSupports masonry applications",
                'packaging' => 'Standard rolls. Confirm pack sizes with sales.',
                'quality_standards' => 'Confirm product specifications with the company before publishing.',
                'is_featured' => false,
                'sort_order' => 2,
                'specs' => [
                    ['label' => 'Category', 'value' => 'Building materials'],
                    ['label' => 'Unit', 'value' => 'Roll'],
                ],
            ],
        ];

        foreach ($products as $data) {
            $specs = $data['specs'];
            unset($data['specs']);

            $product = Product::query()->updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    ...$data,
                    'technical_information' => 'Detailed technical values will be published from approved datasheets.',
                    'is_active' => true,
                    'is_comparable' => true,
                    'seo_title' => $data['name'].' | Simba Cement',
                    'meta_description' => $data['short_description'],
                    'published_at' => now(),
                ]
            );

            $product->specifications()->delete();

            foreach ($specs as $index => $spec) {
                ProductSpecification::query()->create([
                    'product_id' => $product->id,
                    'label' => $spec['label'],
                    'value' => $spec['value'],
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
