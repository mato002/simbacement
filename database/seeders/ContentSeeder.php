<?php

namespace Database\Seeders;

use App\Enums\NewsCategory;
use App\Models\JobListing;
use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('email', 'admin@simbacement.local')->first();

        $articles = [
            [
                'title' => 'Simba Cement Strengthens Supply Across Kenya',
                'category' => NewsCategory::News,
                'excerpt' => 'Expanded distribution support for contractors, developers and hardware partners.',
                'body' => "Simba Cement continues to support construction demand with reliable cement and building materials supply.\n\nOur teams remain focused on quality, availability and responsive quotation support for projects of all sizes.",
            ],
            [
                'title' => 'Quality First: Manufacturing Excellence Update',
                'category' => NewsCategory::Update,
                'excerpt' => 'A look at quality control practices across production and packaging.',
                'body' => "Quality remains central to every batch we produce.\n\nFrom raw material checks to final packaging, our process is designed for consistent performance on site.",
            ],
            [
                'title' => 'Join Our Growing Operations Team',
                'category' => NewsCategory::Event,
                'excerpt' => 'New career pathways are opening across manufacturing and commercial functions.',
                'body' => "We are expanding opportunities for engineers, operators and commercial talent.\n\nVisit the Careers page to view current openings and submit your application.",
            ],
        ];

        foreach ($articles as $index => $data) {
            NewsArticle::query()->updateOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    ...$data,
                    'author_id' => $author?->id,
                    'is_published' => true,
                    'seo_title' => $data['title'].' | Simba Cement',
                    'meta_description' => $data['excerpt'],
                    'published_at' => now()->subDays($index + 1),
                ]
            );
        }

        $jobs = [
            [
                'title' => 'Production Engineer',
                'location' => 'Athi River',
                'department' => 'Manufacturing',
                'employment_type' => 'full-time',
                'summary' => 'Support plant production efficiency, process improvement and quality outcomes.',
                'responsibilities' => "Monitor production performance\nSupport process optimisation\nCollaborate with quality and maintenance teams",
                'requirements' => "Degree in engineering or related field\nPlant/process experience preferred\nStrong problem-solving skills",
            ],
            [
                'title' => 'Sales Executive',
                'location' => 'Nairobi',
                'department' => 'Sales',
                'employment_type' => 'full-time',
                'summary' => 'Drive quotation support and customer relationships with contractors and distributors.',
                'responsibilities' => "Manage customer enquiries\nPrepare quotation follow-ups\nBuild territory relationships",
                'requirements' => "Experience in B2B sales preferred\nExcellent communication skills\nValid driving licence is an advantage",
            ],
            [
                'title' => 'Graduate Trainee — Operations',
                'location' => 'Nakuru',
                'department' => 'Operations',
                'employment_type' => 'graduate',
                'summary' => 'A structured graduate pathway into manufacturing and operations.',
                'responsibilities' => "Rotate across operations functions\nSupport supervisors on daily activities\nComplete assigned learning modules",
                'requirements' => "Recent graduate in engineering, science or business\nWillingness to learn on site\nStrong work ethic",
            ],
        ];

        foreach ($jobs as $job) {
            JobListing::query()->updateOrCreate(
                ['slug' => Str::slug($job['title'])],
                [
                    ...$job,
                    'is_active' => true,
                    'closes_at' => now()->addMonths(2)->toDateString(),
                    'published_at' => now(),
                ]
            );
        }
    }
}
