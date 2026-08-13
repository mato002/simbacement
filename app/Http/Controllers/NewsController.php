<?php

namespace App\Http\Controllers;

use App\Enums\NewsCategory;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();

        $articles = NewsArticle::query()
            ->published()
            ->with(['image', 'author'])
            ->when($category, fn ($q) => $q->where('category', $category))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.news.index', [
            'articles' => $articles,
            'categories' => NewsCategory::cases(),
            'activeCategory' => $category,
        ]);
    }

    public function show(NewsArticle $article): View
    {
        abort_unless(
            $article->is_published && $article->published_at && $article->published_at->lte(now()),
            404
        );

        $article->load(['image', 'author']);

        $related = NewsArticle::query()
            ->published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'image' => $article->imageUrl(),
            'datePublished' => $article->published_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author?->name ?: config('app.name'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
            ],
        ];

        return view('public.news.show', compact('article', 'related', 'schema'));
    }
}
