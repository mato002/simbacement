<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::query()
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.form', [
            'page' => new Page([
                'is_published' => true,
                'sort_order' => 0,
                'sections' => [],
            ]),
            'sectionRows' => [[
                'type' => 'text',
                'title' => '',
                'body' => '',
                'items' => '',
            ]],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $page = Page::query()->create($this->validated($request));

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', "Page “{$page->title}” created.");
    }

    public function edit(Page $page): View
    {
        $sections = old('sections', $page->sections ?: []);

        $sectionRows = collect($sections)->map(function (array $section) {
            return [
                'type' => $section['type'] ?? 'text',
                'title' => $section['title'] ?? '',
                'body' => $section['body'] ?? '',
                'items' => isset($section['items']) && is_array($section['items'])
                    ? implode("\n", $section['items'])
                    : '',
            ];
        })->values()->all();

        if ($sectionRows === []) {
            $sectionRows = [[
                'type' => 'text',
                'title' => '',
                'body' => '',
                'items' => '',
            ]];
        }

        return view('admin.pages.form', [
            'page' => $page,
            'sectionRows' => $sectionRows,
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validated($request, $page));

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', "Page “{$page->title}” updated.");
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Page $page = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('pages', 'slug')->ignore($page?->id)],
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'headline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'hero_image_url' => ['nullable', 'url', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'sections' => ['nullable', 'array'],
            'sections.*.type' => ['required', 'in:text,cards,timeline,process,documents'],
            'sections.*.title' => ['nullable', 'string', 'max:180'],
            'sections.*.body' => ['nullable', 'string'],
            'sections.*.items' => ['nullable', 'string'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['sections'] = collect($data['sections'] ?? [])
            ->map(function (array $section) {
                $items = collect(preg_split("/\r\n|\n|\r/", (string) ($section['items'] ?? '')))
                    ->map(fn ($item) => trim($item))
                    ->filter()
                    ->values()
                    ->all();

                return [
                    'type' => $section['type'],
                    'title' => $section['title'] ?? null,
                    'body' => $section['body'] ?? null,
                    'items' => $items,
                ];
            })
            ->filter(fn (array $section) => filled($section['title']) || filled($section['body']) || $section['items'] !== [])
            ->values()
            ->all();

        return $data;
    }
}
