@extends('layouts.public')

@section('title', ($activeCategory?->seo_title ?: ($activeCategory?->name ? $activeCategory->name.' Products' : 'Products')).' — '.config('app.name'))
@section('meta_description', $activeCategory?->meta_description ?: 'Explore Simba Cement products including cement, steel and building materials. Request a quote for your project.')

@section('content')
    @php
        $activeFilterCount = collect([
            filled($search) ? 'q' : null,
            $activeCategory?->slug,
        ])->filter()->count();
    @endphp

    <section class="border-b border-line bg-white">
        <div class="container-page py-10 sm:py-16">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <p class="section-label mb-3">Catalogue</p>
                    <h1 class="heading-display text-ink">
                        {{ $activeCategory?->name ?: 'Products' }}
                    </h1>
                    <p class="mt-4 text-base text-steel sm:text-lg">
                        {{ $activeCategory?->description ?: 'High-quality cement, steel and building materials engineered for strength, durability and performance.' }}
                    </p>
                </div>
                <a href="{{ route('products.compare') }}" class="btn-dark self-start">
                    <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                    Compare
                </a>
            </div>
        </div>
    </section>

    <section class="py-8 sm:py-10" x-data="{ selected: [], filtersOpen: false }">
        <div class="container-page">
            <div class="filter-toolbar">
                <form method="GET" action="{{ route('products.index') }}" class="relative min-w-0 flex-1">
                    @if ($activeCategory)
                        <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                    @endif
                    <input
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Search products"
                        class="filter-field pr-10"
                    >
                    <button type="submit" class="absolute inset-y-0 right-0 px-3 text-steel" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    </button>
                </form>
                <x-filter-button :count="$activeFilterCount" />
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="mb-8 hidden gap-3 border border-line bg-white p-4 lg:grid lg:grid-cols-[1fr_220px_auto]">
                <input
                    type="search"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search products"
                    class="filter-field"
                >
                <select name="category" class="filter-field">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(($activeCategory?->slug) === $category->slug)>
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary !py-2.5">Filter</button>
            </form>

            <div class="mb-6 hidden flex-wrap gap-2 lg:flex">
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

            @if ($activeFilterCount)
                <div class="mb-4 flex flex-wrap items-center gap-2 lg:hidden">
                    @if (filled($search))
                        <span class="inline-flex items-center gap-2 border border-line bg-white px-2.5 py-1 text-xs font-semibold">
                            “{{ $search }}”
                            <a href="{{ route('products.index', array_filter(['category' => $activeCategory?->slug])) }}" class="text-steel" aria-label="Clear search">&times;</a>
                        </span>
                    @endif
                    @if ($activeCategory)
                        <span class="inline-flex items-center gap-2 border border-line bg-white px-2.5 py-1 text-xs font-semibold">
                            {{ $activeCategory->name }}
                            <a href="{{ route('products.index', array_filter(['q' => $search ?: null])) }}" class="text-steel" aria-label="Clear category">&times;</a>
                        </span>
                    @endif
                </div>
            @endif

            <x-filter-drawer title="Filter products">
                <form method="GET" action="{{ route('products.index') }}" class="space-y-5">
                    <div>
                        <label class="mb-2 block text-xs font-semibold tracking-wide text-steel uppercase">Search</label>
                        <input type="search" name="q" value="{{ $search }}" placeholder="Search products" class="filter-field">
                    </div>
                    <div>
                        <p class="mb-2 text-xs font-semibold tracking-wide text-steel uppercase">Category</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 border border-line px-3 py-3 text-sm {{ ! $activeCategory ? 'border-brand bg-brand/10' : '' }}">
                                <input type="radio" name="category" value="" @checked(! $activeCategory)>
                                All categories
                            </label>
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-3 border border-line px-3 py-3 text-sm {{ ($activeCategory?->slug) === $category->slug ? 'border-brand bg-brand/10' : '' }}">
                                    <input type="radio" name="category" value="{{ $category->slug }}" @checked(($activeCategory?->slug) === $category->slug)>
                                    <span class="flex-1">{{ $category->name }}</span>
                                    <span class="text-xs text-steel">{{ $category->products_count }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="sticky bottom-0 flex gap-2 bg-white pt-2 pb-[max(0.5rem,env(safe-area-inset-bottom))]">
                        <a href="{{ route('products.index') }}" class="btn-dark flex-1 !py-3">Clear</a>
                        <button type="submit" class="btn-primary flex-1 !py-3">Apply</button>
                    </div>
                </form>
            </x-filter-drawer>

            <form method="GET" action="{{ route('products.compare') }}" class="mb-6 flex items-center justify-between gap-3 border border-line bg-white px-3 py-2 sm:static sm:border-0 sm:bg-transparent sm:px-0 sm:py-0" x-show="selected.length" x-cloak>
                <template x-for="slug in selected" :key="slug">
                    <input type="hidden" name="products[]" :value="slug">
                </template>
                <p class="text-sm text-steel"><span x-text="selected.length"></span> selected</p>
                <button type="submit" class="btn-primary !py-2" :disabled="selected.length < 2">Compare</button>
            </form>

            <div class="card-grid">
                @forelse ($products as $product)
                    <article class="card-tile">
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
                                <span class="absolute top-2 left-2 bg-brand px-1.5 py-0.5 text-[10px] font-bold tracking-wide text-ink uppercase sm:top-3 sm:left-3 sm:px-2 sm:py-1 sm:text-[11px]">Featured</span>
                            @endif
                        </a>
                        <div class="card-tile-body">
                            <p class="card-kicker">{{ $product->category?->name }}</p>
                            <h2 class="card-title">
                                <a href="{{ route('products.show', $product) }}" class="hover:text-brand-deep">{{ $product->name }}</a>
                            </h2>
                            <p class="mt-2 hidden line-clamp-3 flex-1 text-sm text-steel sm:block">{{ $product->short_description }}</p>
                            <div class="mt-3 flex flex-wrap items-center gap-2 sm:mt-5 sm:gap-3">
                                <a href="{{ route('quote.create', ['product' => $product->id]) }}" class="btn-primary !px-2.5 !py-1.5 !text-[10px] sm:!px-3 sm:!py-2 sm:!text-xs">
                                    <span class="sm:hidden">Quote</span>
                                    <span class="hidden sm:inline">Request Quote</span>
                                </a>
                                @if ($product->is_comparable)
                                    <label class="flex items-center gap-1.5 text-[10px] font-semibold text-steel sm:ml-auto sm:text-xs">
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
