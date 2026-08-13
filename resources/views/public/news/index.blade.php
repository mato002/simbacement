@extends('layouts.public')

@section('title', 'News & Media — '.config('app.name'))
@section('meta_description', 'Latest news, press releases, company updates and events from Simba Cement.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-14">
            <p class="section-label mb-3">Media</p>
            <h1 class="heading-display text-ink !text-5xl">News & Media</h1>
            <p class="mt-4 max-w-2xl text-lg text-steel">Company updates, press releases and project stories.</p>
        </div>
    </section>

    <section class="py-10">
        <div class="container-page">
            <div class="mb-8 flex flex-wrap gap-2">
                <a href="{{ route('news.index') }}" class="px-3 py-1.5 text-sm font-semibold {{ $activeCategory === '' ? 'bg-ink text-white' : 'border border-line bg-white hover:border-brand' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('news.index', ['category' => $category->value]) }}" class="px-3 py-1.5 text-sm font-semibold {{ $activeCategory === $category->value ? 'bg-ink text-white' : 'border border-line bg-white hover:border-brand' }}">
                        {{ $category->label() }}
                    </a>
                @endforeach
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($articles as $article)
                    <article class="border border-line bg-white transition hover:border-brand">
                        <a href="{{ route('news.show', $article) }}" class="block aspect-[16/10] overflow-hidden bg-concrete">
                            <img src="{{ $article->imageUrl() }}" alt="{{ $article->imageAlt() }}" class="h-full w-full object-cover" loading="lazy">
                        </a>
                        <div class="p-5">
                            <p class="text-xs font-semibold tracking-wide text-brand-deep uppercase">
                                {{ $article->category->label() }} · {{ $article->published_at?->format('d M Y') }}
                            </p>
                            <h2 class="mt-2 font-display text-2xl font-bold uppercase tracking-wide">
                                <a href="{{ route('news.show', $article) }}">{{ $article->title }}</a>
                            </h2>
                            <p class="mt-3 line-clamp-3 text-sm text-steel">{{ $article->excerpt }}</p>
                            <a href="{{ route('news.show', $article) }}" class="mt-5 inline-flex text-sm font-semibold text-brand-deep hover:underline">Read more</a>
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
