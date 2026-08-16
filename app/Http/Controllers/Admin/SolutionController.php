<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Solution;
use App\Services\MediaLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SolutionController extends Controller
{
    public function index(): View
    {
        $solutions = Solution::query()
            ->with('image')
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.solutions.index', compact('solutions'));
    }

    public function create(): View
    {
        return view('admin.solutions.form', [
            'solution' => new Solution(['is_active' => true, 'sort_order' => 0, 'highlights' => []]),
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
            'selectedProducts' => [],
            'highlightRows' => [''],
        ]);
    }

    public function store(Request $request, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $data = $this->validated($request);
        $solution = Solution::query()->create($data);
        $solution->products()->sync($this->productSyncPayload($request));
        $this->syncImage($solution, $request, $mediaLibrary);

        return redirect()
            ->route('admin.solutions.edit', $solution)
            ->with('success', "Solution “{$solution->name}” created.");
    }

    public function edit(Solution $solution): View
    {
        $solution->load('image');
        $highlights = old('highlights', $solution->highlights ?: []);

        return view('admin.solutions.form', [
            'solution' => $solution,
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
            'selectedProducts' => old('products', $solution->products()->pluck('products.id')->all()),
            'highlightRows' => $highlights !== [] ? $highlights : [''],
        ]);
    }

    public function update(Request $request, Solution $solution, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $solution->update($this->validated($request, $solution));
        $solution->products()->sync($this->productSyncPayload($request));
        $this->syncImage($solution, $request, $mediaLibrary);

        return redirect()
            ->route('admin.solutions.edit', $solution)
            ->with('success', "Solution “{$solution->name}” updated.");
    }

    public function destroy(Solution $solution): RedirectResponse
    {
        $solution->delete();

        return redirect()
            ->route('admin.solutions.index')
            ->with('success', 'Solution deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Solution $solution = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('solutions', 'slug')->ignore($solution?->id)],
            'headline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'highlights' => ['nullable', 'array'],
            'highlights.*' => ['nullable', 'string', 'max:160'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'image' => ['nullable', 'image', 'max:5120'],
            'products' => ['nullable', 'array'],
            'products.*' => ['integer', 'exists:products,id'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['highlights'] = collect($data['highlights'] ?? [])
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        unset($data['products'], $data['image']);

        return $data;
    }

    /**
     * @return array<int, array{sort_order: int}>
     */
    private function productSyncPayload(Request $request): array
    {
        $payload = [];
        foreach (array_values($request->input('products', [])) as $index => $productId) {
            $payload[(int) $productId] = ['sort_order' => $index + 1];
        }

        return $payload;
    }

    private function syncImage(Solution $solution, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $media = $mediaLibrary->store(
            $request->file('image'),
            $request->user(),
            'solutions',
            $solution->name
        );

        $solution->update(['image_id' => $media->id]);
    }
}
