@extends('layouts.public')

@section('title', 'News & Media — '.config('app.name'))
@section('meta_description', 'Latest news, press releases, company updates and events from Simba Cement.')

@section('content')
    @php
        $activeFilterCount = $activeCategory === '' ? 0 : 1;
    @endphp

    <section class="border-b border-line bg-white">
        <div class="container-page py-10 sm:py-14">
            <p class="section-label mb-3">Media</p>
            <h1 class="heading-display text-ink">News & Media</h1>
            <p class="mt-4 max-w-2xl text-base text-steel sm:text-lg">Company updates, press releases and project stories.</p>
        </div>
    </section>

    <section class="py-8 sm:py-10" x-data="{ filtersOpen: false }">
        <div class="container-page">
            <div class="filter-toolbar">
                <p class="min-w-0 flex-1 text-sm font-semibold text-ink">
                    {{ $activeCategory === '' ? 'All stories' : (\App\Enums\NewsCategory::tryFrom($activeCategory)?->label() ?? 'All stories') }}
                </p>
                <x-filter-button :count="$activeFilterCount" />
            </div>

            <div class="mb-8 hidden flex-wrap gap-2 lg:flex">
                <a href="{{ route('news.index') }}" class="px-3 py-1.5 text-sm font-semibold {{ $activeCategory === '' ? 'bg-ink text-white' : 'border border-line bg-white hover:border-brand' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('news.index', ['category' => $category->value]) }}" class="px-3 py-1.5 text-sm font-semibold {{ $activeCategory === $category->value ? 'bg-ink text-white' : 'border border-line bg-white hover:border-brand' }}">
                        {{ $category->label() }}
                    </a>
                @endforeach
            </div>

            <x-filter-drawer title="Filter news">
                <form method="GET" action="{{ route('news.index') }}" class="space-y-5">
                    <div>
                        <p class="mb-2 text-xs font-semibold tracking-wide text-steel uppercase">Category</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 border border-line px-3 py-3 text-sm {{ $activeCategory === '' ? 'border-brand bg-brand/10' : '' }}">
                                <input type="radio" name="category" value="" @checked($activeCategory === '')>
                                All stories
                            </label>
                            @foreach ($categories as $category)
                                <label class="flex items-center gap-3 border border-line px-3 py-3 text-sm {{ $activeCategory === $category->value ? 'border-brand bg-brand/10' : '' }}">
                                    <input type="radio" name="category" value="{{ $category->value }}" @checked($activeCategory === $category->value)>
                                    {{ $category->label() }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="sticky bottom-0 flex gap-2 bg-white pt-2 pb-[max(0.5rem,env(safe-area-inset-bottom))]">
                        <a href="{{ route('news.index') }}" class="btn-dark flex-1 !py-3">Clear</a>
                        <button type="submit" class="btn-primary flex-1 !py-3">Apply</button>
                    </div>
                </form>
            </x-filter-drawer>

            <div class="card-grid">
                @forelse ($articles as $article)
                    <article class="card-tile">
                        <a href="{{ route('news.show', $article) }}" class="block aspect-[16/10] overflow-hidden bg-concrete">
                            <img src="{{ $article->imageUrl() }}" alt="{{ $article->imageAlt() }}" class="h-full w-full object-cover" loading="lazy">
                        </a>
                        <div class="card-tile-body">
                            <p class="card-kicker">
                                {{ $article->category->label() }} · {{ $article->published_at?->format('d M Y') }}
                            </p>
                            <h2 class="card-title">
                                <a href="{{ route('news.show', $article) }}">{{ $article->title }}</a>
                            </h2>
                            <p class="mt-2 hidden line-clamp-3 text-sm text-steel sm:block">{{ $article->excerpt }}</p>
                            <a href="{{ route('news.show', $article) }}" class="mt-3 inline-flex text-xs font-semibold text-brand-deep hover:underline sm:mt-5 sm:text-sm">Read more</a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full border border-dashed border-line bg-white p-10 text-center text-steel">No articles published yet.</div>
                @endforelse
            </div>

            <div class="mt-8">{{ $articles->links() }}</div>
        </div>
    </section>
@endsection
