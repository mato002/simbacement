@extends('layouts.public')

@section('title', ($title ?? 'Page') . ' — ' . config('app.name'))
@section('meta_description', $description ?? 'Simba Cement corporate website page.')

@section('content')
    @php $banner = config('media.placeholder'); @endphp

    <section class="relative isolate overflow-hidden border-b border-line bg-ink text-white">
        <div class="absolute inset-0">
            <img
                src="{{ $banner['url'] }}"
                alt="{{ $banner['alt'] }}"
                class="h-full w-full object-cover opacity-40"
                width="1600"
                height="900"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/85 to-ink/50"></div>
        </div>
        <div class="container-page relative py-16 sm:py-20">
            <p class="section-label mb-3 text-brand">{{ $eyebrow ?? 'Coming next' }}</p>
            <h1 class="heading-display">{{ $title }}</h1>
            <p class="mt-5 max-w-2xl text-lg text-white/75">{{ $description }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('quote.create') }}" class="btn-primary">
                    <i class="fa-solid fa-file-signature" aria-hidden="true"></i>
                    Get a Quote
                </a>
                <a href="{{ route('home') }}" class="btn-secondary">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container-page">
            <div class="border border-dashed border-line bg-white p-8">
                <h2 class="flex items-center gap-3 font-display text-2xl font-bold uppercase tracking-wide">
                    <i class="fa-solid fa-screwdriver-wrench text-brand" aria-hidden="true"></i>
                    Phase status
                </h2>
                <p class="mt-3 max-w-3xl text-steel">
                    This page shell is wired into navigation and SEO-ready routing. Full CMS-driven content, media, and forms will land in the planned later phases.
                </p>
                @isset($bullets)
                    <ul class="mt-6 space-y-2 text-sm text-ink/80">
                        @foreach ($bullets as $bullet)
                            <li class="flex gap-2">
                                <i class="fa-solid fa-circle-check mt-0.5 text-brand" aria-hidden="true"></i>
                                <span>{{ $bullet }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endisset
            </div>
        </div>
    </section>
@endsection
