<?php

namespace App\Http\Controllers;

use App\Models\Solution;
use Illuminate\View\View;

class SolutionController extends Controller
{
    public function index(): View
    {
        $solutions = Solution::query()
            ->active()
            ->with('image')
            ->withCount(['products' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('public.solutions.index', compact('solutions'));
    }

    public function show(Solution $solution): View
    {
        abort_unless($solution->is_active, 404);

        $solution->load([
            'image',
            'products' => fn ($q) => $q->published()->with(['category', 'images.media']),
        ]);

        return view('public.solutions.show', compact('solution'));
    }
}
