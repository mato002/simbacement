@extends('layouts.public')

@section('title', ($solution->seo_title ?: $solution->name).' — '.config('app.name'))
@section('meta_description', $solution->meta_description ?: $solution->summary)

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-12 sm:py-16">
            <nav class="mb-6 text-sm text-steel">
                <a href="{{ route('solutions.index') }}" class="hover:text-ink">Solutions</a>
                <span class="mx-2">/</span>
                <span class="text-ink">{{ $solution->name }}</span>
            </nav>

            <div class="grid gap-10 lg:grid-cols-2">
                <div>
                    <p class="section-label mb-3">Solution</p>
                    <h1 class="heading-display text-ink">{{ $solution->name }}</h1>
                    @if ($solution->headline)
                        <p class="mt-4 text-xl text-steel">{{ $solution->headline }}</p>
                    @endif
                    <p class="mt-5 text-steel">{{ $solution->summary }}</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('quote.create') }}" class="btn-primary">Request a Quote</a>
                        <a href="{{ route('products.index') }}" class="btn-dark">Explore Products</a>
                    </div>
                </div>
                <div class="overflow-hidden border border-line bg-concrete">
                    <img src="{{ $solution->imageUrl() }}" alt="{{ $solution->imageAlt() }}" class="aspect-[4/3] h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <section class="py-14">
        <div class="container-page grid gap-10 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Overview</h2>
                <div class="mt-4 max-w-3xl whitespace-pre-line text-steel">{{ $solution->content ?: $solution->summary }}</div>
            </div>

            @if (! empty($solution->highlights))
                <div class="border border-line bg-white p-6">
                    <h2 class="font-display text-2xl font-bold uppercase tracking-wide">Recommended for</h2>
                    <ul class="mt-4 space-y-3">
                        @foreach ($solution->highlights as $highlight)
                            <li class="flex items-start gap-3 text-sm text-ink">
                                <i class="fa-solid fa-check mt-0.5 text-brand" aria-hidden="true"></i>
                                <span>{{ $highlight }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    @if ($solution->products->isNotEmpty())
        <section class="border-t border-line bg-white py-14">
            <div class="container-page">
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Recommended products</h2>
                <div class="mt-8 card-grid">
                    @foreach ($solution->products as $product)
                        <a href="{{ route('products.show', $product) }}" class="card-tile">
                            <div class="aspect-[4/3] overflow-hidden bg-concrete">
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->imageAlt() }}" class="h-full w-full object-cover" loading="lazy">
                            </div>
                            <div class="card-tile-body">
                                <p class="card-kicker">{{ $product->category?->name }}</p>
                                <h3 class="card-title">{{ $product->name }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('quote.create') }}" class="btn-primary mt-8">Request Similar Solution</a>
            </div>
        </section>
    @endif
@endsection
