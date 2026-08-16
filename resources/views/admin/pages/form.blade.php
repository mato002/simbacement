@extends('layouts.admin')

@section('title', $page->exists ? 'Edit Page' : 'Add Page')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Content</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">
            {{ $page->exists ? 'Edit Page' : 'Add Page' }}
        </h1>
    </div>

    <form
        method="POST"
        action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
        x-data="{ sections: {{ \Illuminate\Support\Js::from($sectionRows) }} }"
    >
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-5 border border-line bg-white p-6 xl:col-span-2">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Title</label>
                        <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Eyebrow</label>
                        <input type="text" name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Headline</label>
                        <input type="text" name="headline" value="{{ old('headline', $page->headline) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Summary</label>
                    <textarea name="summary" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('summary', $page->summary) }}</textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Hero image</label>
                    <x-admin.image-field
                        name="hero_image"
                        :existing-url="$page->heroImageUrl()"
                        :existing-alt="$page->title"
                        hint="Upload a new hero, or keep using an external URL below."
                        input-class="mt-3 w-full border border-line bg-mist px-3 py-2 text-sm"
                    />
                    @error('hero_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Hero image URL (optional override)</label>
                    <input type="url" name="hero_image_url" value="{{ old('hero_image_url', $page->hero_image_url) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm" placeholder="https://…">
                </div>

                <div>
                    <div class="mb-3 flex items-center justify-between">
                        <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Sections</h2>
                        <button type="button" class="text-sm font-semibold text-brand-deep" @click="sections.push({ type: 'text', title: '', body: '', items: '' })">Add section</button>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(section, index) in sections" :key="index">
                            <div class="border border-line bg-mist p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <select :name="`sections[${index}][type]`" x-model="section.type" class="border border-line bg-white px-3 py-2 text-sm">
                                        <option value="text">Text</option>
                                        <option value="cards">Cards</option>
                                        <option value="process">Process</option>
                                        <option value="timeline">Timeline</option>
                                        <option value="documents">Documents</option>
                                    </select>
                                    <button type="button" class="text-sm font-semibold text-red-700" @click="sections.splice(index, 1)">Remove</button>
                                </div>
                                <input type="text" :name="`sections[${index}][title]`" x-model="section.title" placeholder="Section title" class="mb-3 w-full border border-line bg-white px-3 py-2.5 text-sm">
                                <textarea :name="`sections[${index}][body]`" x-model="section.body" rows="3" placeholder="Section body" class="mb-3 w-full border border-line bg-white px-3 py-2.5 text-sm"></textarea>
                                <textarea :name="`sections[${index}][items]`" x-model="section.items" rows="4" placeholder="Items (one per line) for cards/process/timeline/documents" class="w-full border border-line bg-white px-3 py-2.5 text-sm"></textarea>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Publishing</h2>
                    <label class="mt-4 flex items-center gap-2 text-sm font-semibold">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published))>
                        Published
                    </label>
                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-semibold">Sort order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $page->sort_order) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">SEO</h2>
                    <div class="mt-4 space-y-4">
                        <input type="text" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}" placeholder="SEO title" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        <textarea name="meta_description" rows="3" placeholder="Meta description" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">{{ $page->exists ? 'Save page' : 'Create page' }}</button>
            <a href="{{ route('admin.pages.index') }}" class="btn-dark">Back</a>
        </div>
    </form>

    @if ($page->exists && ! in_array($page->slug, ['about', 'manufacturing', 'quality', 'sustainability'], true))
        <div class="mt-6">
            <x-admin.delete-form
                :action="route('admin.pages.destroy', $page)"
                label="Delete page"
                title="Delete this page?"
            />
        </div>
    @endif
@endsection
