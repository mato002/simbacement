<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NewsCategory;
use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Services\MediaLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = NewsArticle::query()
            ->with('author')
            ->when($request->string('category')->toString(), fn ($q, $category) => $q->where('category', $category))
            ->latest('published_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.news.index', [
            'articles' => $articles,
            'categories' => NewsCategory::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.news.form', [
            'article' => new NewsArticle([
                'category' => NewsCategory::News,
                'is_published' => false,
            ]),
            'categories' => NewsCategory::cases(),
        ]);
    }

    public function store(Request $request, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;

        $article = NewsArticle::query()->create($data);
        $this->syncImage($article, $request, $mediaLibrary);

        return redirect()
            ->route('admin.news.edit', $article)
            ->with('success', "Article “{$article->title}” created.");
    }

    public function edit(NewsArticle $news): View
    {
        $news->load('image');

        return view('admin.news.form', [
            'article' => $news,
            'categories' => NewsCategory::cases(),
        ]);
    }

    public function update(Request $request, NewsArticle $news, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $news->update($this->validated($request, $news));
        $this->syncImage($news, $request, $mediaLibrary);

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', "Article “{$news->title}” updated.");
    }

    public function destroy(NewsArticle $news): RedirectResponse
    {
        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'Article deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?NewsArticle $article = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('news_articles', 'slug')->ignore($article?->id)],
            'category' => ['required', Rule::enum(NewsCategory::class)],
            'excerpt' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $request->boolean('is_published')
            ? ($article?->published_at ?? now())
            : null;

        unset($data['image']);

        return $data;
    }

    private function syncImage(NewsArticle $article, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $media = $mediaLibrary->store(
            $request->file('image'),
            $request->user(),
            'news',
            $article->title
        );

        $article->update(['image_id' => $media->id]);
    }
}
