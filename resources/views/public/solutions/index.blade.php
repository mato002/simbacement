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
            <h1 class="heading-display">Solutions</h1>
            <p class="mt-4 max-w-2xl text-base text-white/75 sm:text-lg">
                Beyond products — practical guidance for homes, commercial builds, infrastructure and channel partners.
            </p>
        </div>
    </section>

    <section class="py-10 sm:py-14">
        <div class="container-page card-grid">
            @foreach ($solutions as $solution)
                <a href="{{ route('solutions.show', $solution) }}" class="card-tile group">
                    <div class="aspect-[16/10] overflow-hidden bg-concrete">
                        <img src="{{ $solution->imageUrl() }}" alt="{{ $solution->imageAlt() }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    </div>
                    <div class="card-tile-body">
                        <h2 class="card-title">{{ $solution->name }}</h2>
                        <p class="mt-2 hidden text-sm leading-relaxed text-steel sm:mt-3 sm:block">{{ $solution->summary }}</p>
                        <span class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-brand-deep group-hover:underline sm:mt-5 sm:text-sm">
                            Explore
                            <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
