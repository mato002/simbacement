@extends('layouts.public')

@section('title', ($page->seo_title ?: $page->title).' — '.config('app.name'))
@section('meta_description', $page->meta_description ?: $page->summary)

@section('content')
    <section class="relative isolate overflow-hidden bg-ink text-white">
        <div class="absolute inset-0">
            <img src="{{ $page->heroImageUrl() }}" alt="{{ $page->heroImageAlt() }}" class="h-full w-full object-cover opacity-45">
            <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/80 to-ink/40"></div>
        </div>
        <div class="container-page relative py-16 sm:py-20">
            @if ($page->eyebrow)
                <p class="section-label mb-3 text-brand">{{ $page->eyebrow }}</p>
            @endif
            <h1 class="heading-display max-w-4xl !text-5xl">{{ $page->headline ?: $page->title }}</h1>
            @if ($page->summary)
                <p class="mt-5 max-w-2xl text-lg text-white/75">{{ $page->summary }}</p>
            @endif
        </div>
    </section>

    <section class="py-14">
        <div class="container-page space-y-14">
            @foreach ($page->sections ?? [] as $section)
                @php $type = $section['type'] ?? 'text'; @endphp

                @if ($type === 'text')
                    <div class="max-w-3xl">
                        @if (! empty($section['title']))
                            <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $section['title'] }}</h2>
                        @endif
                        @if (! empty($section['body']))
                            <div class="mt-4 whitespace-pre-line text-steel">{{ $section['body'] }}</div>
                        @endif
                    </div>

                @elseif ($type === 'cards')
                    <div>
                        @if (! empty($section['title']))
                            <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $section['title'] }}</h2>
                        @endif
                        @if (! empty($section['body']))
                            <p class="mt-3 max-w-3xl text-steel">{{ $section['body'] }}</p>
                        @endif
                        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($section['items'] ?? [] as $item)
                                <div class="border border-line bg-white p-5">
                                    <div class="flex items-start gap-3">
                                        <i class="fa-solid fa-check mt-1 text-brand" aria-hidden="true"></i>
                                        <p class="text-sm font-semibold text-ink">{{ $item }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @elseif ($type === 'process')
                    <div>
                        @if (! empty($section['title']))
                            <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $section['title'] }}</h2>
                        @endif
                        @if (! empty($section['body']))
                            <p class="mt-3 max-w-3xl text-steel">{{ $section['body'] }}</p>
                        @endif
                        <ol class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            @foreach ($section['items'] ?? [] as $index => $item)
                                <li class="border border-line bg-white p-5">
                                    <p class="font-display text-3xl font-bold text-brand">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</p>
                                    <p class="mt-3 text-sm font-semibold uppercase tracking-wide">{{ $item }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                @elseif ($type === 'timeline')
                    <div>
                        @if (! empty($section['title']))
                            <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $section['title'] }}</h2>
                        @endif
                        @if (! empty($section['body']))
                            <p class="mt-3 max-w-3xl text-steel">{{ $section['body'] }}</p>
                        @endif
                        <div class="mt-8 space-y-0 border-l border-brand/50 pl-6">
                            @foreach ($section['items'] ?? [] as $item)
                                <div class="relative pb-8 last:pb-0">
                                    <span class="absolute -left-[1.9rem] top-1.5 h-3 w-3 rounded-full bg-brand"></span>
                                    <p class="text-sm font-semibold text-ink">{{ $item }}</p>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 text-xs text-steel">Timeline milestones should be verified with official company records before public launch.</p>
                    </div>

                @elseif ($type === 'documents')
                    <div class="border border-line bg-white p-6 sm:p-8">
                        @if (! empty($section['title']))
                            <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $section['title'] }}</h2>
                        @endif
                        @if (! empty($section['body']))
                            <p class="mt-3 max-w-3xl text-steel">{{ $section['body'] }}</p>
                        @endif
                        <ul class="mt-6 space-y-3">
                            @forelse ($section['items'] ?? [] as $item)
                                <li class="flex items-center gap-3 text-sm text-ink">
                                    <i class="fa-solid fa-file-pdf text-brand" aria-hidden="true"></i>
                                    <span>{{ $item }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-steel">Approved documents will appear here once uploaded by the company.</li>
                            @endforelse
                        </ul>
                    </div>
                @endif
            @endforeach

            @if ($page->slug === 'about' && $locations->isNotEmpty())
                <div>
                    <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Our Presence</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        @foreach ($locations as $location)
                            <div class="border border-line bg-white p-5">
                                <p class="text-xs font-semibold tracking-wide text-brand-deep uppercase">{{ $location->type->label() }}</p>
                                <h3 class="mt-1 font-display text-xl font-bold uppercase tracking-wide">{{ $location->name }}</h3>
                                <p class="mt-2 text-sm text-steel">{{ $location->address }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="border border-line bg-white p-6 sm:p-8">
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Talk to our team</h2>
                <p class="mt-3 max-w-2xl text-steel">Need product guidance, technical information or a project quotation?</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('quote.create') }}" class="btn-primary">Request a Quote</a>
                    <a href="{{ route('contact') }}" class="btn-dark">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
@endsection
