<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Services\MediaLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'images.media'])
            ->withCount('images')
            ->when($request->string('category')->toString(), fn ($q, $slug) => $q->whereHas(
                'category',
                fn ($category) => $category->where('slug', $slug)
            ))
            ->when($request->string('q')->toString(), function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => ProductCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product([
                'is_active' => true,
                'is_featured' => false,
                'is_comparable' => true,
                'unit' => 'bag',
                'sort_order' => 0,
            ]),
            'categories' => ProductCategory::query()->active()->orderBy('sort_order')->get(),
            'specRows' => [['label' => '', 'value' => '']],
        ]);
    }

    public function store(Request $request, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $data = $this->validated($request);

        $product = DB::transaction(function () use ($request, $data, $mediaLibrary) {
            $product = Product::query()->create($data);
            $this->syncSpecifications($product, $request->input('specs', []));
            $this->syncPrimaryImage($product, $request, $mediaLibrary);
            $this->syncGalleryImages($product, $request, $mediaLibrary);

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', "Product “{$product->name}” created.");
    }

    public function edit(Product $product): View
    {
        $product->load(['specifications', 'images.media', 'category']);

        $specRows = $product->specifications
            ->map(fn ($spec) => ['label' => $spec->label, 'value' => $spec->value])
            ->values()
            ->all();

        if ($specRows === []) {
            $specRows = [['label' => '', 'value' => '']];
        }

        return view('admin.products.form', [
            'product' => $product,
            'categories' => ProductCategory::query()->orderBy('sort_order')->get(),
            'specRows' => $specRows,
        ]);
    }

    public function update(Request $request, Product $product, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $data = $this->validated($request, $product);

        DB::transaction(function () use ($request, $product, $data, $mediaLibrary) {
            $product->update($data);
            $this->syncSpecifications($product, $request->input('specs', []));
            $this->syncPrimaryImage($product, $request, $mediaLibrary);
            $this->syncGalleryImages($product, $request, $mediaLibrary);
            $this->removeGalleryImages($product, $request, $mediaLibrary);
            $this->removePrimaryImage($product, $request, $mediaLibrary);
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', "Product “{$product->name}” updated.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('products', 'slug')->ignore($product?->id)],
            'sku' => ['nullable', 'string', 'max:80', Rule::unique('products', 'sku')->ignore($product?->id)],
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'overview' => ['nullable', 'string'],
            'technical_information' => ['nullable', 'string'],
            'applications' => ['nullable', 'string'],
            'key_benefits' => ['nullable', 'string'],
            'packaging' => ['nullable', 'string'],
            'quality_standards' => ['nullable', 'string'],
            'unit' => ['required', 'string', 'max:40'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'primary_image' => ['nullable', 'image', 'max:5120'],
            'remove_primary_image' => ['sometimes', 'boolean'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
            'specs' => ['nullable', 'array'],
            'specs.*.label' => ['nullable', 'string', 'max:120'],
            'specs.*.value' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_comparable'] = $request->boolean('is_comparable');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['published_at'] = $request->boolean('is_published')
            ? ($product?->published_at ?? now())
            : null;

        unset($data['primary_image'], $data['remove_primary_image'], $data['gallery_images'], $data['remove_images'], $data['specs']);

        return $data;
    }

    /**
     * @param  array<int, array{label?: string|null, value?: string|null}>  $specs
     */
    private function syncSpecifications(Product $product, array $specs): void
    {
        $product->specifications()->delete();

        $order = 1;
        foreach ($specs as $spec) {
            $label = trim((string) ($spec['label'] ?? ''));
            $value = trim((string) ($spec['value'] ?? ''));

            if ($label === '' || $value === '') {
                continue;
            }

            $product->specifications()->create([
                'label' => $label,
                'value' => $value,
                'sort_order' => $order++,
            ]);
        }
    }

    private function syncPrimaryImage(Product $product, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->hasFile('primary_image')) {
            return;
        }

        $media = $mediaLibrary->store(
            $request->file('primary_image'),
            $request->user(),
            'products',
            $product->name
        );

        $existingPrimary = $product->images()->where('is_primary', true)->with('media')->first();

        if ($existingPrimary) {
            $oldMedia = $existingPrimary->media;
            $existingPrimary->update(['media_id' => $media->id]);

            if ($oldMedia && $oldMedia->id !== $media->id) {
                $stillUsed = ProductImage::query()->where('media_id', $oldMedia->id)->exists()
                    || Product::query()->where('og_image_id', $oldMedia->id)->where('id', '!=', $product->id)->exists();

                if (! $stillUsed) {
                    $mediaLibrary->delete($oldMedia);
                }
            }
        } else {
            ProductImage::query()->create([
                'product_id' => $product->id,
                'media_id' => $media->id,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        $product->update(['og_image_id' => $media->id]);
    }

    private function removePrimaryImage(Product $product, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->boolean('remove_primary_image') || $request->hasFile('primary_image')) {
            return;
        }

        $primary = $product->images()->where('is_primary', true)->with('media')->first();

        if (! $primary) {
            return;
        }

        $oldMedia = $primary->media;
        $primary->delete();

        if ($product->og_image_id && $oldMedia && $product->og_image_id === $oldMedia->id) {
            $product->update(['og_image_id' => null]);
        }

        if ($oldMedia) {
            $stillUsed = ProductImage::query()->where('media_id', $oldMedia->id)->exists()
                || Product::query()->where('og_image_id', $oldMedia->id)->exists();

            if (! $stillUsed) {
                $mediaLibrary->delete($oldMedia);
            }
        }

        $fallback = $product->images()->with('media')->orderBy('sort_order')->first();
        if ($fallback) {
            $fallback->update(['is_primary' => true]);
            $product->update(['og_image_id' => $fallback->media_id]);
        }
    }

    private function syncGalleryImages(Product $product, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->hasFile('gallery_images')) {
            return;
        }

        $sort = (int) $product->images()->max('sort_order');

        foreach ($request->file('gallery_images', []) as $file) {
            if (! $file) {
                continue;
            }

            $media = $mediaLibrary->store($file, $request->user(), 'products', $product->name);

            ProductImage::query()->create([
                'product_id' => $product->id,
                'media_id' => $media->id,
                'is_primary' => false,
                'sort_order' => ++$sort,
            ]);
        }
    }

    private function removeGalleryImages(Product $product, Request $request, MediaLibraryService $mediaLibrary): void
    {
        $ids = collect($request->input('remove_images', []))->map(fn ($id) => (int) $id)->filter()->all();

        if ($ids === []) {
            return;
        }

        $images = $product->images()->with('media')->whereIn('id', $ids)->get();

        foreach ($images as $image) {
            if ($image->is_primary) {
                continue;
            }

            $oldMedia = $image->media;
            $image->delete();

            if ($oldMedia) {
                $stillUsed = ProductImage::query()->where('media_id', $oldMedia->id)->exists()
                    || Product::query()->where('og_image_id', $oldMedia->id)->exists();

                if (! $stillUsed) {
                    $mediaLibrary->delete($oldMedia);
                }
            }
        }
    }
}
