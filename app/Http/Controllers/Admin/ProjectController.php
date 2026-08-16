<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectCategory;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\MediaLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->with('featuredImage')
            ->when($request->string('category')->toString(), fn ($q, $category) => $q->where('category', $category))
            ->orderByDesc('year')
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'categories' => ProjectCategory::cases(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project([
                'is_published' => false,
                'is_featured' => false,
                'sort_order' => 0,
                'year' => (int) now()->format('Y'),
            ]),
            'categories' => ProjectCategory::cases(),
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
            'selectedProducts' => [],
        ]);
    }

    public function store(Request $request, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $data = $this->validated($request);
        $project = Project::query()->create($data);
        $project->products()->sync($request->input('products', []));
        $this->syncFeaturedImage($project, $request, $mediaLibrary);
        $this->syncGalleryImages($project, $request, $mediaLibrary);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', "Project “{$project->title}” created.");
    }

    public function edit(Project $project): View
    {
        $project->load(['featuredImage', 'images.media']);

        return view('admin.projects.form', [
            'project' => $project,
            'categories' => ProjectCategory::cases(),
            'products' => Product::query()->orderBy('name')->get(['id', 'name']),
            'selectedProducts' => old('products', $project->products()->pluck('products.id')->all()),
        ]);
    }

    public function update(Request $request, Project $project, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $project->update($this->validated($request, $project));
        $project->products()->sync($request->input('products', []));
        $this->syncFeaturedImage($project, $request, $mediaLibrary);
        $this->syncGalleryImages($project, $request, $mediaLibrary);
        $this->removeGalleryImages($project, $request, $mediaLibrary);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', "Project “{$project->title}” updated.");
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('projects', 'slug')->ignore($project?->id)],
            'location' => ['nullable', 'string', 'max:160'],
            'client' => ['nullable', 'string', 'max:160'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'category' => ['required', Rule::enum(ProjectCategory::class)],
            'summary' => ['nullable', 'string'],
            'overview' => ['nullable', 'string'],
            'challenge' => ['nullable', 'string'],
            'solution' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
            'products' => ['nullable', 'array'],
            'products.*' => ['integer', 'exists:products,id'],
        ]);

        $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['title']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['published_at'] = $request->boolean('is_published')
            ? ($project?->published_at ?? now())
            : null;

        unset($data['featured_image'], $data['gallery_images'], $data['remove_images'], $data['products']);

        return $data;
    }

    private function syncFeaturedImage(Project $project, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->hasFile('featured_image')) {
            return;
        }

        $media = $mediaLibrary->store(
            $request->file('featured_image'),
            $request->user(),
            'projects',
            $project->title
        );

        $project->update(['featured_image_id' => $media->id]);
    }

    private function syncGalleryImages(Project $project, Request $request, MediaLibraryService $mediaLibrary): void
    {
        if (! $request->hasFile('gallery_images')) {
            return;
        }

        $sort = (int) $project->images()->max('sort_order');

        foreach ($request->file('gallery_images', []) as $file) {
            $media = $mediaLibrary->store($file, $request->user(), 'projects', $project->title);

            ProjectImage::query()->create([
                'project_id' => $project->id,
                'media_id' => $media->id,
                'sort_order' => ++$sort,
            ]);
        }
    }

    private function removeGalleryImages(Project $project, Request $request, MediaLibraryService $mediaLibrary): void
    {
        $ids = collect($request->input('remove_images', []))->map(fn ($id) => (int) $id)->filter()->all();

        if ($ids === []) {
            return;
        }

        $images = $project->images()->with('media')->whereIn('id', $ids)->get();

        foreach ($images as $image) {
            if ($image->media) {
                $mediaLibrary->delete($image->media);
            }

            $image->delete();
        }
    }
}
