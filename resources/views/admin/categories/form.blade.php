@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Category' : 'Add Category')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Catalogue</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">
            {{ $category->exists ? 'Edit Category' : 'Add Category' }}
        </h1>
    </div>

    <form
        method="POST"
        action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
        class="max-w-3xl space-y-5 border border-line bg-white p-6"
    >
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm" placeholder="auto-generated">
                @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-semibold">Parent category</label>
            <select name="parent_id" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                <option value="">None</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-semibold">Description</label>
            <textarea name="description" rows="4" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Sort order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-sm font-semibold">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))>
                    Active
                </label>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold">SEO title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $category->seo_title) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Meta description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $category->meta_description) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
        </div>

        <div class="flex flex-wrap gap-3 pt-2">
            <button type="submit" class="btn-primary">{{ $category->exists ? 'Save changes' : 'Create category' }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-dark">Cancel</a>
        </div>
    </form>

    @if ($category->exists)
        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-6" onsubmit="return confirm('Delete this category?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Delete category</button>
        </form>
    @endif
@endsection
