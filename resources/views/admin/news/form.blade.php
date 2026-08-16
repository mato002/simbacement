@extends('layouts.admin')

@section('title', $article->exists ? 'Edit Article' : 'Add Article')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Content</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">{{ $article->exists ? 'Edit Article' : 'Add Article' }}</h1>
    </div>

    <form method="POST" action="{{ $article->exists ? route('admin.news.update', $article) : route('admin.news.store') }}" enctype="multipart/form-data" class="max-w-4xl space-y-5 border border-line bg-white p-6">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Title</label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $article->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-semibold">Category</label>
            <select name="category" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                @foreach ($categories as $category)
                    <option value="{{ $category->value }}" @selected(old('category', $article->category?->value) === $category->value)>{{ $category->label() }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-semibold">Excerpt</label>
            <textarea name="excerpt" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-semibold">Body</label>
            <textarea name="body" rows="10" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('body', $article->body) }}</textarea>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-semibold">Featured image</label>
            @if ($article->image)
                <img src="{{ $article->image->url() }}" alt="" class="mb-3 aspect-video w-full max-w-md object-cover">
            @endif
            <input type="file" name="image" accept="image/*" class="w-full border border-line bg-mist px-3 py-2 text-sm">
        </div>
        <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->is_published))> Published</label>
        <div class="grid gap-5 md:grid-cols-2">
            <input type="text" name="seo_title" value="{{ old('seo_title', $article->seo_title) }}" placeholder="SEO title" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <input type="text" name="meta_description" value="{{ old('meta_description', $article->meta_description) }}" placeholder="Meta description" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
        </div>
        <div class="flex gap-3">
            <button class="btn-primary">{{ $article->exists ? 'Save article' : 'Create article' }}</button>
            <a href="{{ route('admin.news.index') }}" class="btn-dark">Back</a>
        </div>
    </form>

    @if ($article->exists)
        <div class="mt-6">
            <x-admin.delete-form
                :action="route('admin.news.destroy', $article)"
                label="Delete article"
                title="Delete this article?"
            />
        </div>
    @endif
@endsection
