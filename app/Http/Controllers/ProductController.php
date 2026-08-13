<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = $request->string('category')->toString();
        $search = $request->string('q')->toString();

        $categories = ProductCategory::query()
            ->active()
            ->roots()
            ->withCount(['products' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->published()
            ->with(['category', 'images.media', 'specifications'])
            ->when($categorySlug, fn ($q) => $q->whereHas(
                'category',
                fn ($category) => $category->where('slug', $categorySlug)
            ))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $activeCategory = $categorySlug
            ? $categories->firstWhere('slug', $categorySlug)
            : null;

        return view('public.products.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'search' => $search,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless(
            $product->is_active && $product->published_at && $product->published_at->lte(now()),
            404
        );

        $product->load([
            'category',
            'images.media',
            'specifications',
            'datasheets.media',
            'solutions',
        ]);

        $related = Product::query()
            ->published()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'images.media'])
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->short_description,
            'sku' => $product->sku,
            'image' => $product->imageUrl(),
            'brand' => [
                '@type' => 'Brand',
                'name' => config('app.name'),
            ],
            'category' => $product->category?->name,
        ];

        return view('public.products.show', compact('product', 'related', 'schema'));
    }

    public function compare(Request $request): View
    {
        $slugs = collect($request->input('products', []))
            ->filter()
            ->unique()
            ->take(4)
            ->values();

        $selected = Product::query()
            ->published()
            ->where('is_comparable', true)
            ->whereIn('slug', $slugs)
            ->with(['category', 'specifications', 'images.media'])
            ->orderBy('sort_order')
            ->get();

        $comparable = Product::query()
            ->published()
            ->where('is_comparable', true)
            ->with('category')
            ->orderBy('name')
            ->get();

        $labels = $selected
            ->flatMap(fn (Product $product) => $product->specifications->pluck('label'))
            ->unique()
            ->values();

        return view('public.products.compare', [
            'selected' => $selected,
            'comparable' => $comparable,
            'labels' => $labels,
            'selectedSlugs' => $slugs->all(),
        ]);
    }
}
