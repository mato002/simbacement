@extends('layouts.admin')

@section('title', $project->exists ? 'Edit Project' : 'Add Project')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Portfolio</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">
            {{ $project->exists ? 'Edit Project' : 'Add Project' }}
        </h1>
    </div>

    <form
        method="POST"
        action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf
        @if ($project->exists) @method('PUT') @endif

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-5 border border-line bg-white p-6 xl:col-span-2">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Title</label>
                        <input type="text" name="title" value="{{ old('title', $project->title) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $project->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Location</label>
                        <input type="text" name="location" value="{{ old('location', $project->location) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Client</label>
                        <input type="text" name="client" value="{{ old('client', $project->client) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Year</label>
                        <input type="number" name="year" value="{{ old('year', $project->year) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Category</label>
                    <select name="category" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @foreach ($categories as $category)
                            <option value="{{ $category->value }}" @selected(old('category', $project->category?->value) === $category->value)>{{ $category->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Summary</label>
                    <textarea name="summary" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('summary', $project->summary) }}</textarea>
                </div>
                @foreach (['overview' => 'Overview', 'challenge' => 'Challenge', 'solution' => 'Solution'] as $field => $label)
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">{{ $label }}</label>
                        <textarea name="{{ $field }}" rows="4" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old($field, $project->{$field}) }}</textarea>
                    </div>
                @endforeach
            </div>

            <div class="space-y-5">
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Publishing</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <label class="flex items-center gap-2 font-semibold"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $project->is_published))> Published</label>
                        <label class="flex items-center gap-2 font-semibold"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))> Featured</label>
                    </div>
                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-semibold">Sort order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $project->sort_order) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Featured image</h2>
                    @if ($project->featuredImage)
                        <img src="{{ $project->featuredImage->url() }}" alt="{{ $project->title }}" class="mt-4 aspect-video w-full object-cover bg-mist">
                    @endif
                    <input type="file" name="featured_image" accept="image/*" class="mt-4 w-full border border-line bg-mist px-3 py-2 text-sm">
                </div>
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Gallery</h2>
                    <p class="mt-2 text-xs text-steel">Extra project photos for the public gallery.</p>
                    @if ($project->exists && $project->images->isNotEmpty())
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            @foreach ($project->images as $image)
                                <label class="relative block overflow-hidden border border-line bg-mist">
                                    @if ($image->media)
                                        <img src="{{ $image->media->url() }}" alt="" class="aspect-square w-full object-cover">
                                    @endif
                                    <span class="absolute inset-x-0 bottom-0 bg-ink/80 px-2 py-1 text-[11px] font-semibold text-white">
                                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="mr-1">
                                        Remove
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                    <input type="file" name="gallery_images[]" accept="image/*" multiple class="mt-4 w-full border border-line bg-mist px-3 py-2 text-sm">
                </div>
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Products used</h2>
                    <div class="mt-4 max-h-72 space-y-2 overflow-y-auto">
                        @foreach ($products as $product)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="products[]" value="{{ $product->id }}" @checked(in_array($product->id, $selectedProducts, true))>
                                {{ $product->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">SEO</h2>
                    <div class="mt-4 space-y-4">
                        <input type="text" name="seo_title" value="{{ old('seo_title', $project->seo_title) }}" placeholder="SEO title" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        <textarea name="meta_description" rows="3" placeholder="Meta description" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('meta_description', $project->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">{{ $project->exists ? 'Save project' : 'Create project' }}</button>
            <a href="{{ route('admin.projects.index') }}" class="btn-dark">Back</a>
        </div>
    </form>

    @if ($project->exists)
        <div class="mt-6">
            <x-admin.delete-form
                :action="route('admin.projects.destroy', $project)"
                label="Delete project"
                title="Delete this project?"
            />
        </div>
    @endif
@endsection
