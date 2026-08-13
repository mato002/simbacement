<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class CorporatePageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'eyebrow' => 'Company',
                'headline' => 'About Simba Cement',
                'summary' => 'Official manufacturer of high-quality cement and building materials engineered for strength, durability and performance across Kenya.',
                'sort_order' => 1,
                'seo_title' => 'About Us | Simba Cement',
                'meta_description' => 'Learn about Simba Cement — our story, vision, mission, values and manufacturing presence.',
                'sections' => [
                    [
                        'type' => 'text',
                        'title' => 'Our Story',
                        'body' => "Simba Cement is committed to supplying dependable cement and building materials for Kenya’s construction sector.\n\nFrom residential builds to infrastructure and industrial projects, our focus is consistent quality, practical product guidance and reliable customer support.\n\nCompany history milestones below are placeholders until confirmed by official records.",
                        'items' => [],
                    ],
                    [
                        'type' => 'cards',
                        'title' => 'Vision, Mission & Values',
                        'body' => 'The principles that guide how we manufacture, supply and support construction partners.',
                        'items' => [
                            'Vision: To be a trusted cement manufacturer supporting Kenya’s built environment.',
                            'Mission: Deliver quality cement and building materials with dependable service.',
                            'Value: Quality — consistency in every batch and every delivery promise.',
                            'Value: Integrity — clear communication and responsible business practice.',
                            'Value: Safety — protecting people, sites and communities.',
                            'Value: Partnership — supporting contractors, developers and distributors.',
                        ],
                    ],
                    [
                        'type' => 'timeline',
                        'title' => 'Our Journey',
                        'body' => 'Key milestones in the company’s growth. Replace with verified dates and events before launch.',
                        'items' => [
                            'Company established',
                            'Factory expansion and capacity growth',
                            'New product lines introduced',
                            'Distribution network strengthened',
                            'Latest quality and sustainability initiatives',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'title' => 'Leadership & Manufacturing',
                        'body' => "Leadership and manufacturing details will be published once approved by the company.\n\nOur operations combine process discipline, quality control and distribution capability to serve construction demand nationwide.",
                        'items' => [],
                    ],
                ],
            ],
            [
                'title' => 'Manufacturing',
                'slug' => 'manufacturing',
                'eyebrow' => 'Operations',
                'headline' => 'Manufacturing Excellence',
                'summary' => 'From raw materials to packaging and distribution — a controlled process designed for consistent cement performance.',
                'sort_order' => 2,
                'seo_title' => 'Manufacturing | Simba Cement',
                'meta_description' => 'Explore the Simba Cement manufacturing process, technology, laboratories and distribution.',
                'sections' => [
                    [
                        'type' => 'process',
                        'title' => 'Our Manufacturing Process',
                        'body' => 'A clear production flow that supports quality at every stage.',
                        'items' => [
                            'Raw Materials',
                            'Processing',
                            'Grinding',
                            'Blending',
                            'Quality Control',
                            'Packaging',
                            'Distribution',
                        ],
                    ],
                    [
                        'type' => 'cards',
                        'title' => 'Technology & Laboratories',
                        'body' => 'Capabilities that support reliable output for construction customers.',
                        'items' => [
                            'Process monitoring throughout production',
                            'Laboratory testing for quality assurance',
                            'Controlled packaging standards',
                            'Distribution readiness for project supply',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'title' => 'Factory Photography & Video',
                        'body' => "Factory media will be added from approved company photography and video assets.\n\nUntil then, this page presents the operational narrative and process stages customers can expect.",
                        'items' => [],
                    ],
                ],
            ],
            [
                'title' => 'Quality',
                'slug' => 'quality',
                'eyebrow' => 'Standards',
                'headline' => 'Quality at Every Stage',
                'summary' => 'Quality control across raw materials, production, laboratory testing and finished product release.',
                'sort_order' => 3,
                'seo_title' => 'Quality | Simba Cement',
                'meta_description' => 'Discover Simba Cement quality control, testing and standards documentation.',
                'sections' => [
                    [
                        'type' => 'cards',
                        'title' => 'Quality Controls',
                        'body' => 'How we protect product consistency from input to dispatch.',
                        'items' => [
                            'Raw material testing',
                            'Production monitoring',
                            'Laboratory testing',
                            'Product testing',
                            'Batch control',
                            'Standards & certification readiness',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'title' => 'Standards & Certification',
                        'body' => "Only company-approved standards, certificates and claims should be published.\n\nThis page is structured to host those documents once they are supplied and cleared for public use.",
                        'items' => [],
                    ],
                    [
                        'type' => 'documents',
                        'title' => 'Downloadable Documents',
                        'body' => 'Product datasheets, technical specifications, certificates and safety documentation will be listed here after approval.',
                        'items' => [
                            'Product Datasheets — pending company upload',
                            'Technical Specifications — pending company upload',
                            'Certificates — pending company upload',
                            'Safety Documentation — pending company upload',
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Sustainability',
                'slug' => 'sustainability',
                'eyebrow' => 'Responsibility',
                'headline' => 'Sustainable Manufacturing',
                'summary' => 'Environmental responsibility, safer operations and community-minded growth around our manufacturing footprint.',
                'sort_order' => 4,
                'seo_title' => 'Sustainability | Simba Cement',
                'meta_description' => 'Learn about Simba Cement sustainability priorities across environment, efficiency, safety and community.',
                'sections' => [
                    [
                        'type' => 'cards',
                        'title' => 'Our Focus Areas',
                        'body' => 'Priority themes for responsible manufacturing and stakeholder impact.',
                        'items' => [
                            'Environmental responsibility',
                            'Energy efficiency',
                            'Waste reduction',
                            'Responsible manufacturing',
                            'Community development',
                            'Employee safety',
                        ],
                    ],
                    [
                        'type' => 'text',
                        'title' => 'Environmental Initiatives',
                        'body' => "Detailed metrics and initiative reports can be published once verified by the company.\n\nThis page provides the structure for environmental, social and safety storytelling as approved content becomes available.",
                        'items' => [],
                    ],
                ],
            ],
        ];

        foreach ($pages as $page) {
            Page::query()->updateOrCreate(
                ['slug' => $page['slug']],
                [
                    ...$page,
                    'is_published' => true,
                ]
            );
        }
    }
}
