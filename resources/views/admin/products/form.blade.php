@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Catalogue</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">
            {{ $product->exists ? 'Edit Product' : 'Add Product' }}
        </h1>
    </div>

    <form
        method="POST"
        action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}"
        enctype="multipart/form-data"
        class="space-y-6"
        x-data="{ specs: {{ \Illuminate\Support\Js::from(old('specs', $specRows)) }} }"
    >
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="space-y-5 border border-line bg-white p-6 xl:col-span-2">
                <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Product details</h2>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Name</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Category</label>
                        <select name="category_id" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Tagline</label>
                    <input type="text" name="tagline" value="{{ old('tagline', $product->tagline) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold">Short description</label>
                    <textarea name="short_description" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('short_description', $product->short_description) }}</textarea>
                </div>

                @foreach ([
                    'overview' => 'Overview',
                    'technical_information' => 'Technical information',
                    'applications' => 'Applications',
                    'key_benefits' => 'Key benefits',
                    'packaging' => 'Packaging',
                    'quality_standards' => 'Quality & standards',
                ] as $field => $label)
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold">{{ $label }}</label>
                        <textarea name="{{ $field }}" rows="4" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old($field, $product->{$field}) }}</textarea>
                    </div>
                @endforeach

                <div>
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="font-display text-xl font-bold uppercase tracking-wide">Specifications</h3>
                        <button type="button" class="text-sm font-semibold text-brand-deep hover:underline" @click="specs.push({ label: '', value: '' })">Add row</button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(spec, index) in specs" :key="index">
                            <div class="grid gap-3 md:grid-cols-[1fr_1fr_auto]">
                                <input type="text" :name="`specs[${index}][label]`" x-model="spec.label" placeholder="Label" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                                <input type="text" :name="`specs[${index}][value]`" x-model="spec.value" placeholder="Value" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                                <button type="button" class="px-3 text-sm font-semibold text-red-700" @click="specs.splice(index, 1)">Remove</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Publishing</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <label class="flex items-center gap-2 font-semibold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Active</label>
                        <label class="flex items-center gap-2 font-semibold"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', (bool) $product->published_at))> Published</label>
                        <label class="flex items-center gap-2 font-semibold"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured</label>
                        <label class="flex items-center gap-2 font-semibold"><input type="checkbox" name="is_comparable" value="1" @checked(old('is_comparable', $product->is_comparable))> Comparable</label>
                    </div>
                    <div class="mt-4">
                        <label class="mb-1.5 block text-sm font-semibold">Sort order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $product->sort_order) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    </div>
                </div>

                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Primary image</h2>
                    @php $primary = $product->exists ? $product->primaryImage() : null; @endphp
                    @if ($primary?->media)
                        <img src="{{ $primary->media->url() }}" alt="{{ $product->name }}" class="mt-4 aspect-square w-full object-cover bg-mist">
                    @endif
                    <input type="file" name="primary_image" accept="image/*" class="mt-4 w-full border border-line bg-mist px-3 py-2 text-sm">
                    @error('primary_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Gallery</h2>
                    <p class="mt-2 text-xs text-steel">Add extra product photos shown on the product page.</p>
                    @if ($product->exists && $product->images->where('is_primary', false)->isNotEmpty())
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            @foreach ($product->images->where('is_primary', false) as $image)
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
                    @error('gallery_images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">SEO</h2>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold">SEO title</label>
                            <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold">Meta description</label>
                            <textarea name="meta_description" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('meta_description', $product->meta_description) }}</textarea>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold">Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="btn-primary">{{ $product->exists ? 'Save product' : 'Create product' }}</button>
            <a href="{{ route('admin.products.index') }}" class="btn-dark">Back to products</a>
        </div>
    </form>

    @if ($product->exists)
        <div class="mt-6">
            <x-admin.delete-form
                :action="route('admin.products.destroy', $product)"
                label="Delete product"
                title="Delete this product?"
                text="This removes the product from the catalogue."
            />
        </div>
    @endif
@endsection
