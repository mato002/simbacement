@extends('layouts.public')

@section('title', 'Solutions — '.config('app.name'))
@section('meta_description', 'Construction solutions from Simba Cement for residential, commercial, infrastructure, industrial and distribution partners.')

@section('content')
    <section class="relative isolate overflow-hidden bg-ink text-white">
        <div class="absolute inset-0">
            <img src="{{ config('media.solutions.url') }}" alt="{{ config('media.solutions.alt') }}" class="h-full w-full object-cover opacity-40">
            <div class="absolute inset-0 bg-ink/75"></div>
        </div>
        <div class="container-page relative py-16 sm:py-20">
            <p class="section-label mb-3 text-brand">Applications</p>
            <h1 class="heading-display !text-5xl">Solutions</h1>
            <p class="mt-4 max-w-2xl text-lg text-white/75">
                Beyond products — practical guidance for homes, commercial builds, infrastructure and channel partners.
            </p>
        </div>
    </section>

    <section class="py-14">
        <div class="container-page grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($solutions as $solution)
                <a href="{{ route('solutions.show', $solution) }}" class="group border border-line bg-white transition hover:border-brand">
                    <div class="aspect-[16/10] overflow-hidden bg-concrete">
                        <img src="{{ $solution->imageUrl() }}" alt="{{ $solution->imageAlt() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    </div>
                    <div class="p-6">
                        <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $solution->name }}</h2>
                        <p class="mt-3 text-sm leading-relaxed text-steel">{{ $solution->summary }}</p>
                        <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand-deep group-hover:underline">
                            Explore solution
                            <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
