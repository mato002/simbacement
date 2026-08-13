<?php

namespace App\Http\Controllers;

use App\Enums\ProjectCategory;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->toString();

        $projects = Project::query()
            ->published()
            ->with(['featuredImage', 'products'])
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->paginate(9)
            ->withQueryString();

        return view('public.projects.index', [
            'projects' => $projects,
            'categories' => ProjectCategory::cases(),
            'activeCategory' => $category,
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless(
            $project->is_published && $project->published_at && $project->published_at->lte(now()),
            404
        );

        $project->load([
            'featuredImage',
            'images.media',
            'products' => fn ($q) => $q->published()->with(['category', 'images.media']),
        ]);

        $related = Project::query()
            ->published()
            ->where('category', $project->category)
            ->where('id', '!=', $project->id)
            ->with('featuredImage')
            ->orderByDesc('year')
            ->limit(3)
            ->get();

        return view('public.projects.show', compact('project', 'related'));
    }
}
