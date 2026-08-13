@extends('layouts.admin')

@section('title', $solution->exists ? 'Edit Solution' : 'Add Solution')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Content</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">
            {{ $solution->exists ? 'Edit Solution' : 'Add Solution' }}
        </h1>
    </div>

    <form
        method="POST"
        action="{{ $solution->exists ? route('admin.solutions.update', $solution) : route('admin.solutions.store') }}"
        class="space-y-6"
        x-data="{ highlights: {{ \Illuminate\Support\Js::from($highlightRows) }} }"
    >
        @csrf
        @if ($solution->exists) @method('PUT') @endif

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-5 border border-line bg-white p-6 xl:col-span-2">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Name</label>
                        <input type="text" name="name" value="{{ old('name', $solution->name) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $solution->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Headline</label>
                    <input type="text" name="headline" value="{{ old('headline', $solution->headline) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Summary</label>
                    <textarea name="summary" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('summary', $solution->summary) }}</textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Content</label>
                    <textarea name="content" rows="6" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('content', $solution->content) }}</textarea>
                </div>
                <div>
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="font-display text-xl font-bold uppercase tracking-wide">Highlights</h3>
                        <button type="button" class="text-sm font-semibold text-brand-deep" @click="highlights.push('')">Add</button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(item, index) in highlights" :key="index">
                            <div class="flex gap-3">
                                <input type="text" :name="`highlights[${index}]`" x-model="highlights[index]" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                                <button type="button" class="text-sm font-semibold text-red-700" @click="highlights.splice(index, 1)">Remove</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Publishing</h2>
                    <label class="mt-4 flex items-center gap-2 text-sm font-semibold">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $solution->is_active))> Active
                    </label>
                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-semibold">Sort order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $solution->sort_order) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Recommended products</h2>
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
                        <input type="text" name="seo_title" value="{{ old('seo_title', $solution->seo_title) }}" placeholder="SEO title" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        <textarea name="meta_description" rows="3" placeholder="Meta description" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('meta_description', $solution->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">{{ $solution->exists ? 'Save solution' : 'Create solution' }}</button>
            <a href="{{ route('admin.solutions.index') }}" class="btn-dark">Back</a>
        </div>
    </form>

    @if ($solution->exists)
        <form method="POST" action="{{ route('admin.solutions.destroy', $solution) }}" class="mt-6" onsubmit="return confirm('Delete this solution?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Delete solution</button>
        </form>
    @endif
@endsection
