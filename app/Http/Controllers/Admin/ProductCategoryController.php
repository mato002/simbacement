<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Services\MediaLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::query()
            ->with(['parent', 'image'])
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.form', [
            'category' => new ProductCategory(['is_active' => true, 'sort_order' => 0]),
            'parents' => ProductCategory::query()->roots()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $category = ProductCategory::query()->create($this->validated($request));
        $this->syncImage($category, $request, $mediaLibrary);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category “{$category->name}” created.");
    }

    public function edit(ProductCategory $category): View
    {
        $category->load('image');

        return view('admin.categories.form', [
            'category' => $category,
            'parents' => ProductCategory::query()
                ->roots()
                ->where('id', '!=', $category->id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, ProductCategory $category, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $category->update($this->validated($request, $category));
        $this->syncImage($category, $request, $mediaLibrary);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Category “{$category->name}” updated.");
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Move or delete products in this category first.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?ProductCategory $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('product_categories', 'slug')->ignore($category?->id)],
            'parent_id' => ['nullable', 'exists:product_categories,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        unset($data['image']);

        return $data;
    }

    private function syncImage(ProductCategory $category, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->hasFile('image')) {
            return;
        }

        $media = $mediaLibrary->store(
            $request->file('image'),
            $request->user(),
            'categories',
            $category->name
        );

        $category->update(['image_id' => $media->id]);
    }
}
