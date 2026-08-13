<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Product;
use App\Models\Project;
use App\Models\Solution;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('about'), 'priority' => '0.8'],
            ['loc' => route('manufacturing'), 'priority' => '0.8'],
            ['loc' => route('quality'), 'priority' => '0.8'],
            ['loc' => route('sustainability'), 'priority' => '0.8'],
            ['loc' => route('products.index'), 'priority' => '0.9'],
            ['loc' => route('solutions.index'), 'priority' => '0.8'],
            ['loc' => route('projects.index'), 'priority' => '0.8'],
            ['loc' => route('news.index'), 'priority' => '0.7'],
            ['loc' => route('careers.index'), 'priority' => '0.7'],
            ['loc' => route('contact'), 'priority' => '0.8'],
            ['loc' => route('quote.create'), 'priority' => '0.9'],
        ]);

        foreach (Product::query()->published()->get(['slug', 'updated_at']) as $product) {
            $urls->push([
                'loc' => route('products.show', $product),
                'lastmod' => $product->updated_at?->toAtomString(),
                'priority' => '0.8',
            ]);
        }

        foreach (Solution::query()->active()->get(['slug', 'updated_at']) as $solution) {
            $urls->push([
                'loc' => route('solutions.show', $solution),
                'lastmod' => $solution->updated_at?->toAtomString(),
                'priority' => '0.7',
            ]);
        }

        foreach (Project::query()->published()->get(['slug', 'updated_at']) as $project) {
            $urls->push([
                'loc' => route('projects.show', $project),
                'lastmod' => $project->updated_at?->toAtomString(),
                'priority' => '0.7',
            ]);
        }

        foreach (NewsArticle::query()->published()->get(['slug', 'updated_at', 'published_at']) as $article) {
            $urls->push([
                'loc' => route('news.show', $article),
                'lastmod' => ($article->updated_at ?? $article->published_at)?->toAtomString(),
                'priority' => '0.6',
            ]);
        }

        foreach (Page::query()->published()->get(['slug', 'updated_at']) as $page) {
            $urls->push([
                'loc' => url('/'.$page->slug),
                'lastmod' => $page->updated_at?->toAtomString(),
                'priority' => '0.7',
            ]);
        }

        $xml = view('seo.sitemap', ['urls' => $urls->unique('loc')->values()])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
