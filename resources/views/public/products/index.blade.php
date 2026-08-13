@extends('layouts.public')

@section('title', ($activeCategory?->seo_title ?: ($activeCategory?->name ? $activeCategory->name.' Products' : 'Products')).' — '.config('app.name'))
@section('meta_description', $activeCategory?->meta_description ?: 'Explore Simba Cement products including cement, steel and building materials. Request a quote for your project.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-14 sm:py-16">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="section-label mb-3">Catalogue</p>
                    <h1 class="heading-display text-ink !text-5xl">
                        {{ $activeCategory?->name ?: 'Products' }}
                    </h1>
                    <p class="mt-4 text-lg text-steel">
                        {{ $activeCategory?->description ?: 'High-quality cement, steel and building materials engineered for strength, durability and performance.' }}
                    </p>
                </div>
                <a href="{{ route('products.compare') }}" class="btn-dark">
                    <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                    Compare Products
                </a>
            </div>
        </div>
    </section>

    <section class="py-10" x-data="{ selected: [] }">
        <div class="container-page">
            <form method="GET" action="{{ route('products.index') }}" class="mb-8 grid gap-3 border border-line bg-white p-4 md:grid-cols-[1fr_220px_auto]">
                <input
                    type="search"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search products"
                    class="w-full border border-line bg-mist px-3 py-2.5 text-sm"
                >
                <select name="category" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(($activeCategory?->slug) === $category->slug)>
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary !py-2.5">Filter</button>
            </form>

            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('products.index', array_filter(['q' => $search ?: null])) }}"
                   class="px-3 py-1.5 text-sm font-semibold {{ ! $activeCategory ? 'bg-ink text-white' : 'border border-line bg-white text-ink hover:border-brand' }}">
                    All
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', array_filter(['category' => $category->slug, 'q' => $search ?: null])) }}"
                       class="px-3 py-1.5 text-sm font-semibold {{ ($activeCategory?->slug) === $category->slug ? 'bg-ink text-white' : 'border border-line bg-white text-ink hover:border-brand' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('products.compare') }}" class="mb-6 flex items-center justify-between gap-3" x-show="selected.length" x-cloak>
                <template x-for="slug in selected" :key="slug">
                    <input type="hidden" name="products[]" :value="slug">
                </template>
                <p class="text-sm text-steel"><span x-text="selected.length"></span> selected for comparison</p>
                <button type="submit" class="btn-primary !py-2" :disabled="selected.length < 2">Compare selected</button>
            </form>

            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($products as $product)
                    <article class="flex flex-col border border-line bg-white transition hover:border-brand">
                        <a href="{{ route('products.show', $product) }}" class="relative block aspect-[4/3] overflow-hidden bg-concrete">
                            <img
                                src="{{ $product->imageUrl() }}"
                                alt="{{ $product->imageAlt() }}"
                                class="h-full w-full object-cover transition duration-500 hover:scale-105"
                                loading="lazy"
                                width="800"
                                height="600"
                            >
                            @if ($product->is_featured)
                                <span class="absolute top-3 left-3 bg-brand px-2 py-1 text-[11px] font-bold tracking-wide text-ink uppercase">Featured</span>
                            @endif
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                            <p class="text-xs font-semibold tracking-wide text-brand-deep uppercase">{{ $product->category?->name }}</p>
                            <h2 class="mt-2 font-display text-2xl font-bold uppercase tracking-wide">
                                <a href="{{ route('products.show', $product) }}" class="hover:text-brand-deep">{{ $product->name }}</a>
                            </h2>
                            <p class="mt-2 line-clamp-3 flex-1 text-sm text-steel">{{ $product->short_description }}</p>
                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <a href="{{ route('products.show', $product) }}" class="text-sm font-semibold text-brand-deep hover:underline">View details</a>
                                <a href="{{ route('quote.create', ['product' => $product->id]) }}" class="btn-primary !px-3 !py-2 !text-xs">Request Quote</a>
                                @if ($product->is_comparable)
                                    <label class="ml-auto flex items-center gap-2 text-xs font-semibold text-steel">
                                        <input type="checkbox" value="{{ $product->slug }}" @change="selected.includes($event.target.value) ? selected = selected.filter(s => s !== $event.target.value) : selected.push($event.target.value)">
                                        Compare
                                    </label>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full border border-dashed border-line bg-white p-10 text-center text-steel">
                        No products found for this filter.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection
