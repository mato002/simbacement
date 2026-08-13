@extends('layouts.public')

@section('title', ($article->seo_title ?: $article->title).' — '.config('app.name'))
@section('meta_description', $article->meta_description ?: $article->excerpt)

@push('head')
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <article class="border-b border-line bg-white">
        <div class="container-page py-12 sm:py-16">
            <nav class="mb-6 text-sm text-steel">
                <a href="{{ route('news.index') }}" class="hover:text-ink">News & Media</a>
                <span class="mx-2">/</span>
                <span class="text-ink">{{ $article->title }}</span>
            </nav>

            <p class="section-label mb-3">{{ $article->category->label() }}</p>
            <h1 class="heading-display max-w-4xl text-ink !text-5xl">{{ $article->title }}</h1>
            <p class="mt-4 text-sm text-steel">
                {{ $article->published_at?->format('d M Y') }}
                @if ($article->author) · {{ $article->author->name }} @endif
            </p>

            <div class="mt-8 overflow-hidden border border-line bg-concrete">
                <img src="{{ $article->imageUrl() }}" alt="{{ $article->imageAlt() }}" class="aspect-[21/9] w-full object-cover">
            </div>

            @if ($article->excerpt)
                <p class="mt-8 max-w-3xl text-xl text-steel">{{ $article->excerpt }}</p>
            @endif

            <div class="prose mt-8 max-w-3xl whitespace-pre-line text-steel">{{ $article->body }}</div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="py-14">
            <div class="container-page">
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Related articles</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach ($related as $item)
                        <a href="{{ route('news.show', $item) }}" class="border border-line hover:border-brand">
                            <div class="aspect-[16/10] overflow-hidden bg-concrete">
                                <img src="{{ $item->imageUrl() }}" alt="{{ $item->imageAlt() }}" class="h-full w-full object-cover" loading="lazy">
                            </div>
                            <div class="p-4">
                                <p class="text-xs font-semibold tracking-wide text-brand-deep uppercase">{{ $item->category->label() }}</p>
                                <h3 class="mt-1 font-display text-xl font-bold uppercase tracking-wide">{{ $item->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
