<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Page;
use Illuminate\View\View;

class CorporatePageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $locations = $slug === 'about'
            ? Location::query()->active()->orderBy('sort_order')->get()
            : collect();

        return view('public.corporate.show', compact('page', 'locations'));
    }
}
