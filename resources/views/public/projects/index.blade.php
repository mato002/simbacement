@extends('layouts.public')

@section('title', 'Projects — '.config('app.name'))
@section('meta_description', 'Explore Simba Cement project portfolio across residential, commercial, infrastructure and industrial construction.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-14 sm:py-16">
            <p class="section-label mb-3">Portfolio</p>
            <h1 class="heading-display text-ink !text-5xl">Projects</h1>
            <p class="mt-4 max-w-2xl text-lg text-steel">
                Case studies showing how Simba Cement products support real construction outcomes.
            </p>
        </div>
    </section>

    <section class="py-10">
        <div class="container-page">
            <div class="mb-8 flex flex-wrap gap-2">
                <a href="{{ route('projects.index') }}"
                   class="px-3 py-1.5 text-sm font-semibold {{ $activeCategory === '' ? 'bg-ink text-white' : 'border border-line bg-white hover:border-brand' }}">
                    All
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('projects.index', ['category' => $category->value]) }}"
                       class="px-3 py-1.5 text-sm font-semibold {{ $activeCategory === $category->value ? 'bg-ink text-white' : 'border border-line bg-white hover:border-brand' }}">
                        {{ $category->label() }}
                    </a>
                @endforeach
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($projects as $project)
                    <article class="border border-line bg-white transition hover:border-brand">
                        <a href="{{ route('projects.show', $project) }}" class="block aspect-[16/10] overflow-hidden bg-concrete">
                            <img src="{{ $project->imageUrl() }}" alt="{{ $project->imageAlt() }}" class="h-full w-full object-cover transition duration-500 hover:scale-105" loading="lazy">
                        </a>
                        <div class="p-5">
                            <p class="text-xs font-semibold tracking-wide text-brand-deep uppercase">{{ $project->category->label() }}</p>
                            <h2 class="mt-2 font-display text-2xl font-bold uppercase tracking-wide">
                                <a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
                            </h2>
                            <p class="mt-2 text-sm text-steel">{{ $project->location }} @if($project->year) · {{ $project->year }} @endif</p>
                            <p class="mt-3 line-clamp-3 text-sm text-steel">{{ $project->summary }}</p>
                            <a href="{{ route('projects.show', $project) }}" class="mt-5 inline-flex text-sm font-semibold text-brand-deep hover:underline">View project</a>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full border border-dashed border-line bg-white p-10 text-center text-steel">
                        No projects published in this category yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        </div>
    </section>
@endsection
