@extends('layouts.public')

@section('title', config('app.name') . ' — Building Kenya. Building the Future.')
@section('meta_description', 'Simba Cement delivers high-quality cement and building materials engineered for strength, durability and performance.')

@section('content')
    @php
        $hero = config('media.hero');
        $categoriesMedia = config('media.categories');
        $solutionsMedia = config('media.solutions');
        $ctaMedia = config('media.cta');
    @endphp

    {{-- Hero --}}
    <section class="relative isolate min-h-[68vh] overflow-hidden bg-ink text-white sm:min-h-[78vh]">
        <div class="absolute inset-0">
            <img
                src="{{ $hero['url'] }}"
                alt="{{ $hero['alt'] }}"
                class="h-full w-full object-cover"
                width="2000"
                height="1200"
                fetchpriority="high"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/85 to-ink/45"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(230,180,34,0.22),transparent_42%)]"></div>
        </div>

        <div class="container-page relative flex min-h-[68vh] flex-col justify-center py-14 sm:min-h-[78vh] sm:py-20">
            <p class="section-label mb-5 text-brand">Simba Cement</p>
            <h1 class="heading-display max-w-4xl">
                Building Kenya.<br>
                Building the Future.
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/75">
                High-quality cement solutions engineered for strength, durability and performance.
            </p>
            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="btn-primary">
                    <i class="fa-solid fa-cubes" aria-hidden="true"></i>
                    Explore Products
                </a>
                <a href="{{ route('quote.create') }}" class="btn-secondary">
                    <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
                    Request a Quote
                </a>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    @php
        $homeStats = [
            ['value' => $siteStats['years_experience'] ?? null, 'label' => 'Years of Experience', 'icon' => 'fa-solid fa-calendar-check'],
            ['value' => $siteStats['products_count'] ?? null, 'label' => 'Products', 'icon' => 'fa-solid fa-box-open'],
            ['value' => $siteStats['distribution_points'] ?? null, 'label' => 'Distribution Points', 'icon' => 'fa-solid fa-truck'],
            ['value' => $siteStats['projects_served'] ?? null, 'label' => 'Projects Served', 'icon' => 'fa-solid fa-helmet-safety'],
        ];
        $hasVerifiedStats = collect($homeStats)->contains(fn ($stat) => filled($stat['value']));
    @endphp
    <section class="border-y border-line bg-white">
        <div class="container-page grid grid-cols-2 gap-6 py-10 md:grid-cols-4">
            @foreach ($homeStats as $stat)
                <div>
                    <i class="{{ $stat['icon'] }} mb-3 text-brand" aria-hidden="true"></i>
                    <p class="font-display text-4xl font-bold text-ink">{{ filled($stat['value']) ? $stat['value'] : 'XX+' }}</p>
                    <p class="mt-1 text-sm text-steel">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
        @unless ($hasVerifiedStats)
            <div class="container-page pb-8">
                <p class="text-xs text-steel">Figures shown as placeholders until verified in Settings.</p>
            </div>
        @endunless
    </section>

    {{-- Categories --}}
    <section class="py-12 sm:py-20">
        <div class="container-page">
            <div class="mb-10 max-w-2xl">
                <p class="section-label mb-3">Product Categories</p>
                <h2 class="heading-display text-ink !text-4xl sm:!text-5xl">Cement. Steel. Building Materials.</h2>
                <p class="mt-4 text-steel">Browse the live catalogue with dedicated product pages, specifications, and quote actions.</p>
            </div>

            @php
                $categoryIcons = [
                    'cement' => 'fa-solid fa-industry',
                    'steel' => 'fa-solid fa-bars-staggered',
                    'building-materials' => 'fa-solid fa-layer-group',
                ];
                $categoryImageKeys = [
                    'cement' => 'cement',
                    'steel' => 'steel',
                    'building-materials' => 'materials',
                ];
            @endphp

            <div class="card-grid">
                @foreach ($categories as $category)
                    @php
                        $imageKey = $categoryImageKeys[$category->slug] ?? null;
                        $fallback = $imageKey && isset($categoriesMedia[$imageKey])
                            ? $categoriesMedia[$imageKey]
                            : config('media.placeholder');
                        $imageUrl = $category->image?->url() ?: $fallback['url'];
                        $imageAlt = $category->image?->alt ?: ($fallback['alt'] ?? $category->name);
                    @endphp
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="card-tile group">
                        <div class="relative aspect-[16/10] overflow-hidden bg-concrete">
                            <img
                                src="{{ $imageUrl }}"
                                alt="{{ $imageAlt }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                loading="lazy"
                                width="1200"
                                height="750"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-ink/70 to-transparent"></div>
                            <span class="absolute bottom-3 left-3 inline-flex h-8 w-8 items-center justify-center bg-brand text-ink sm:bottom-4 sm:left-4 sm:h-10 sm:w-10">
                                <i class="{{ $categoryIcons[$category->slug] ?? 'fa-solid fa-cube' }}" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="card-tile-body">
                            <h3 class="card-title sm:text-3xl">{{ $category->name }}</h3>
                            <p class="mt-2 hidden text-sm leading-relaxed text-steel sm:mt-3 sm:block">{{ $category->description }}</p>
                            <span class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-brand-deep group-hover:underline sm:mt-6 sm:text-sm">
                                View {{ $category->products_count }} products
                                <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if ($featuredProducts->isNotEmpty())
        <section class="border-y border-line bg-white py-12 sm:py-20">
            <div class="container-page">
                <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-2xl">
                        <p class="section-label mb-3">Featured</p>
                        <h2 class="heading-display text-ink !text-4xl sm:!text-5xl">Key products</h2>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn-dark">View all products</a>
                </div>
                <div class="card-grid-4">
                    @foreach ($featuredProducts as $product)
                        <a href="{{ route('products.show', $product) }}" class="card-tile group">
                            <div class="aspect-[4/3] overflow-hidden bg-concrete">
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->imageAlt() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="card-tile-body">
                                <p class="card-kicker">{{ $product->category?->name }}</p>
                                <h3 class="card-title">{{ $product->name }}</h3>
                                <p class="mt-2 hidden line-clamp-2 text-sm text-steel sm:block">{{ $product->short_description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Solutions strip --}}
    <section class="relative isolate overflow-hidden bg-ink py-12 text-white sm:py-20">
        <div class="absolute inset-0">
            <img
                src="{{ $solutionsMedia['url'] }}"
                alt="{{ $solutionsMedia['alt'] }}"
                class="h-full w-full object-cover opacity-35"
                loading="lazy"
                width="1800"
                height="1000"
            >
            <div class="absolute inset-0 bg-ink/80"></div>
        </div>
        <div class="container-page relative">
            <div class="mb-10 max-w-2xl">
                <p class="section-label mb-3 text-brand">Solutions</p>
                <h2 class="heading-display !text-4xl sm:!text-5xl">Built for every construction need</h2>
            </div>
            @php
                $solutionIcons = [
                    'residential-construction' => 'fa-solid fa-house',
                    'commercial-buildings' => 'fa-solid fa-building',
                    'infrastructure' => 'fa-solid fa-road-bridge',
                    'road-construction' => 'fa-solid fa-road',
                    'industrial-construction' => 'fa-solid fa-warehouse',
                    'developers' => 'fa-solid fa-city',
                    'contractors' => 'fa-solid fa-helmet-safety',
                    'hardware-distributors' => 'fa-solid fa-store',
                ];
            @endphp
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                @foreach ($solutions as $solution)
                    <a href="{{ route('solutions.show', $solution) }}" class="flex items-center gap-3 border border-white/15 px-4 py-4 text-sm font-semibold transition hover:border-brand hover:bg-white/5">
                        <i class="{{ $solutionIcons[$solution->slug] ?? 'fa-solid fa-cube' }} text-brand" aria-hidden="true"></i>
                        {{ $solution->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if ($featuredProjects->isNotEmpty())
        <section class="py-12 sm:py-20">
            <div class="container-page">
                <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-2xl">
                        <p class="section-label mb-3">Portfolio</p>
                        <h2 class="heading-display text-ink !text-4xl sm:!text-5xl">Featured projects</h2>
                    </div>
                    <a href="{{ route('projects.index') }}" class="btn-dark">View all projects</a>
                </div>
                <div class="card-grid">
                    @foreach ($featuredProjects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="card-tile group">
                            <div class="aspect-[16/10] overflow-hidden bg-concrete">
                                <img src="{{ $project->imageUrl() }}" alt="{{ $project->imageAlt() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="card-tile-body">
                                <p class="card-kicker">{{ $project->category->label() }}</p>
                                <h3 class="card-title">{{ $project->title }}</h3>
                                <p class="mt-1 text-[11px] text-steel sm:mt-2 sm:text-sm">{{ $project->location }}@if($project->year) · {{ $project->year }}@endif</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="py-12 sm:py-20">
        <div class="container-page">
            <div class="relative overflow-hidden border border-line bg-white">
                <div class="grid lg:grid-cols-2">
                    <div class="relative min-h-64 overflow-hidden lg:min-h-full">
                        <img
                            src="{{ $ctaMedia['url'] }}"
                            alt="{{ $ctaMedia['alt'] }}"
                            class="absolute inset-0 h-full w-full object-cover"
                            loading="lazy"
                            width="1600"
                            height="1000"
                        >
                    </div>
                    <div class="relative px-6 py-12 sm:px-10">
                        <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-brand/20 blur-2xl"></div>
                        <div class="relative max-w-2xl">
                            <p class="section-label mb-3">Sales Desk</p>
                            <h2 class="heading-display !text-4xl text-ink">Ready to request a quotation?</h2>
                            <p class="mt-4 text-steel">Tell us your product needs, quantities and delivery location. Our team will respond with a reference number.</p>
                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ route('quote.create') }}" class="btn-primary">
                                    <i class="fa-solid fa-file-signature" aria-hidden="true"></i>
                                    Get a Quote
                                </a>
                                <a href="{{ route('contact') }}" class="btn-dark">
                                    <i class="fa-solid fa-phone" aria-hidden="true"></i>
                                    Contact Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
