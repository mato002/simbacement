@extends('layouts.public')

@section('title', ($project->seo_title ?: $project->title).' — '.config('app.name'))
@section('meta_description', $project->meta_description ?: $project->summary)

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-12 sm:py-16">
            <nav class="mb-6 text-sm text-steel">
                <a href="{{ route('projects.index') }}" class="hover:text-ink">Projects</a>
                <span class="mx-2">/</span>
                <span class="text-ink">{{ $project->title }}</span>
            </nav>

            <div class="grid gap-10 lg:grid-cols-2">
                <div class="overflow-hidden border border-line bg-concrete">
                    <img src="{{ $project->imageUrl() }}" alt="{{ $project->imageAlt() }}" class="aspect-[4/3] h-full w-full object-cover">
                </div>
                <div>
                    <p class="section-label mb-3">{{ $project->category->label() }}</p>
                    <h1 class="heading-display text-ink">{{ $project->title }}</h1>
                    <dl class="mt-6 grid grid-cols-2 gap-3 text-sm">
                        <div><dt class="text-steel">Location</dt><dd class="font-semibold">{{ $project->location ?: '—' }}</dd></div>
                        <div><dt class="text-steel">Client</dt><dd class="font-semibold">{{ $project->client ?: '—' }}</dd></div>
                        <div><dt class="text-steel">Year</dt><dd class="font-semibold">{{ $project->year ?: '—' }}</dd></div>
                        <div><dt class="text-steel">Category</dt><dd class="font-semibold">{{ $project->category->label() }}</dd></div>
                    </dl>
                    <p class="mt-5 text-steel">{{ $project->summary }}</p>
                    <a href="{{ route('quote.create') }}" class="btn-primary mt-8">Request Similar Solution</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-14">
        <div class="container-page space-y-10">
            @foreach ([
                'overview' => 'Project Overview',
                'challenge' => 'Challenge',
                'solution' => 'Solution',
            ] as $field => $heading)
                @if (filled($project->{$field}))
                    <div class="border-b border-line pb-10">
                        <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $heading }}</h2>
                        <div class="mt-4 max-w-3xl whitespace-pre-line text-steel">{{ $project->{$field} }}</div>
                    </div>
                @endif
            @endforeach

            @if ($project->products->isNotEmpty())
                <div class="border-b border-line pb-10">
                    <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Products Used</h2>
                    <div class="mt-6 grid grid-cols-2 gap-3 lg:grid-cols-3">
                        @foreach ($project->products as $product)
                            <a href="{{ route('products.show', $product) }}" class="border border-line p-3 hover:border-brand sm:p-4">
                                <p class="card-kicker">{{ $product->category?->name }}</p>
                                <p class="card-title">{{ $product->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($project->images->isNotEmpty())
                <div>
                    <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Project Gallery</h2>
                    <div class="mt-6 grid grid-cols-2 gap-3 lg:grid-cols-3">
                        @foreach ($project->images as $image)
                            <figure class="overflow-hidden border border-line bg-concrete">
                                <img src="{{ $image->media->url() }}" alt="{{ $image->caption ?: $project->title }}" class="aspect-[4/3] w-full object-cover" loading="lazy">
                                @if ($image->caption)
                                    <figcaption class="px-3 py-2 text-xs text-steel">{{ $image->caption }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-line bg-white py-14">
            <div class="container-page">
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Related projects</h2>
                <div class="mt-8 card-grid">
                    @foreach ($related as $item)
                        <a href="{{ route('projects.show', $item) }}" class="card-tile">
                            <div class="aspect-[16/10] overflow-hidden bg-concrete">
                                <img src="{{ $item->imageUrl() }}" alt="{{ $item->imageAlt() }}" class="h-full w-full object-cover" loading="lazy">
                            </div>
                            <div class="card-tile-body">
                                <p class="card-kicker">{{ $item->category->label() }}</p>
                                <h3 class="card-title">{{ $item->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
