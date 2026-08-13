<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Solution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SolutionSeeder extends Seeder
{
    public function run(): void
    {
        $solutions = [
            [
                'name' => 'Residential Construction',
                'headline' => 'Building your home?',
                'summary' => 'Cement and steel solutions for foundations, slabs, columns, walls and floors.',
                'highlights' => ['Foundations', 'Slabs', 'Columns', 'Walls', 'Floors'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Commercial Buildings',
                'headline' => 'Strength for commercial projects',
                'summary' => 'Reliable materials for offices, retail centres and mixed-use developments.',
                'highlights' => ['Structural frames', 'Floor systems', 'Durable finishes'],
                'sort_order' => 2,
            ],
            [
                'name' => 'Infrastructure',
                'headline' => 'Built for national infrastructure',
                'summary' => 'Performance-focused products for bridges, public works and major civil projects.',
                'highlights' => ['Civil works', 'Public infrastructure', 'Long-term durability'],
                'sort_order' => 3,
            ],
            [
                'name' => 'Road Construction',
                'headline' => 'Materials for road and civil works',
                'summary' => 'Support for road construction teams and related infrastructure projects.',
                'highlights' => ['Civil packages', 'Contractor supply', 'Project support'],
                'sort_order' => 4,
            ],
            [
                'name' => 'Industrial Construction',
                'headline' => 'Industrial-grade construction materials',
                'summary' => 'Cement and reinforcement for factories, warehouses and industrial facilities.',
                'highlights' => ['Warehouses', 'Factories', 'Heavy structures'],
                'sort_order' => 5,
            ],
            [
                'name' => 'Developers',
                'headline' => 'Partnering with developers',
                'summary' => 'Product guidance and quotation support for multi-unit and large developments.',
                'highlights' => ['Volume supply', 'Technical guidance', 'Project quotations'],
                'sort_order' => 6,
            ],
            [
                'name' => 'Contractors',
                'headline' => 'Built around contractor needs',
                'summary' => 'Fast quotation workflows and product availability for active construction sites.',
                'highlights' => ['Site supply', 'Product mix', 'Responsive quotations'],
                'sort_order' => 7,
            ],
            [
                'name' => 'Hardware & Distributors',
                'headline' => 'Supply for hardware and distribution networks',
                'summary' => 'Cement, steel and building materials for channel partners across Kenya.',
                'highlights' => ['Channel supply', 'Product range', 'Partner support'],
                'sort_order' => 8,
            ],
        ];

        $featuredProductIds = Product::query()
            ->where('is_featured', true)
            ->pluck('id');

        foreach ($solutions as $data) {
            $solution = Solution::query()->updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'headline' => $data['headline'],
                    'summary' => $data['summary'],
                    'content' => $data['summary'],
                    'highlights' => $data['highlights'],
                    'is_active' => true,
                    'sort_order' => $data['sort_order'],
                    'seo_title' => $data['name'].' Solutions | Simba Cement',
                    'meta_description' => $data['summary'],
                ]
            );

            $solution->products()->sync(
                $featuredProductIds->mapWithKeys(fn ($id, $index) => [$id => ['sort_order' => $index + 1]])->all()
            );
        }
    }
}
