<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Solution;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $categories = ProductCategory::query()
            ->active()
            ->roots()
            ->withCount(['products' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::query()
            ->published()
            ->featured()
            ->with(['category', 'images.media'])
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $solutions = Solution::query()
            ->active()
            ->orderBy('sort_order')
            ->get(['name', 'slug']);

        $featuredProjects = Project::query()
            ->published()
            ->where('is_featured', true)
            ->with('featuredImage')
            ->orderByDesc('year')
            ->limit(3)
            ->get();

        return view('public.home', compact(
            'categories',
            'featuredProducts',
            'solutions',
            'featuredProjects',
        ));
    }
}
