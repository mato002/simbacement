<?php

namespace Database\Seeders;

use App\Enums\ProjectCategory;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = Product::query()
            ->whereIn('slug', [
                'simba-cement-325r',
                'simba-cement-425n',
                'd10',
                'd12',
            ])
            ->pluck('id');

        $projects = [
            [
                'title' => 'Nairobi Residential Estate',
                'location' => 'Nairobi',
                'client' => 'Private Developer',
                'year' => 2024,
                'category' => ProjectCategory::Residential,
                'summary' => 'Multi-unit residential development using Simba Cement for foundations, slabs and structural works.',
                'overview' => 'A residential estate project requiring consistent cement performance across foundations, masonry and floor slabs.',
                'challenge' => 'Deliver reliable material quality for phased construction with tight programme milestones.',
                'solution' => 'Specified Simba Cement 32.5R for general works and 42.5N for structural elements, supported by reinforcement supply.',
            ],
            [
                'title' => 'Commercial Plaza Fit-Out',
                'location' => 'Mombasa',
                'client' => 'Commercial Client',
                'year' => 2023,
                'category' => ProjectCategory::Commercial,
                'summary' => 'Commercial building works supported with cement and steel reinforcement for structural upgrades.',
                'overview' => 'A commercial plaza project focused on durable structural and finishing applications.',
                'challenge' => 'Balance strength requirements with practical site logistics for a busy urban location.',
                'solution' => 'Used premium cement grades and D10/D12 reinforcement packages aligned to structural drawings.',
            ],
            [
                'title' => 'County Road Works Package',
                'location' => 'Nakuru',
                'client' => 'Infrastructure Contractor',
                'year' => 2025,
                'category' => ProjectCategory::Infrastructure,
                'summary' => 'Road and civil works package supplied with cement products for culverts and supporting structures.',
                'overview' => 'Infrastructure package involving civil structures that required dependable cement performance.',
                'challenge' => 'Maintain quality consistency across distributed site locations.',
                'solution' => 'Coordinated quotation and supply of cement grades suited to civil and structural applications.',
            ],
            [
                'title' => 'Industrial Warehouse Expansion',
                'location' => 'Athi River',
                'client' => 'Industrial Operator',
                'year' => 2024,
                'category' => ProjectCategory::Industrial,
                'summary' => 'Warehouse expansion using higher-strength cement and heavy reinforcement for industrial loading conditions.',
                'overview' => 'An industrial expansion requiring durable slabs and structural frames for operational loads.',
                'challenge' => 'Meet higher structural demands while keeping delivery schedules predictable.',
                'solution' => 'Specified Simba Cement 42.5N with D12/D16 reinforcement for primary structural elements.',
            ],
        ];

        foreach ($projects as $index => $data) {
            $project = Project::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    ...$data,
                    'is_featured' => $index < 2,
                    'is_published' => true,
                    'sort_order' => $index + 1,
                    'seo_title' => $data['title'].' | Simba Cement Projects',
                    'meta_description' => $data['summary'],
                    'published_at' => now()->subDays($index + 1),
                ]
            );

            $project->products()->sync($productIds->take(3)->all());
        }
    }
}
