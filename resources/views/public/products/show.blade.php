@extends('layouts.public')

@section('title', ($product->seo_title ?: $product->name).' — '.config('app.name'))
@section('meta_description', $product->meta_description ?: $product->short_description)

@push('head')
    <meta property="og:title" content="{{ $product->seo_title ?: $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?: $product->short_description }}">
    <meta property="og:image" content="{{ $product->imageUrl() }}">
    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-10 sm:py-14">
            <nav class="mb-6 text-sm text-steel" aria-label="Breadcrumb">
                <a href="{{ route('products.index') }}" class="hover:text-ink">Products</a>
                <span class="mx-2">/</span>
                @if ($product->category)
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-ink">{{ $product->category->name }}</a>
                    <span class="mx-2">/</span>
                @endif
                <span class="text-ink">{{ $product->name }}</span>
            </nav>

            <div class="grid gap-10 lg:grid-cols-2">
                <div>
                    <div class="overflow-hidden border border-line bg-concrete">
                        <img
                            src="{{ $product->imageUrl() }}"
                            alt="{{ $product->imageAlt() }}"
                            class="aspect-[4/3] h-full w-full object-cover"
                            width="1200"
                            height="900"
                        >
                    </div>
                    @php $gallery = $product->images->where('is_primary', false)->filter(fn ($image) => $image->media); @endphp
                    @if ($gallery->isNotEmpty())
                        <div class="mt-3 grid grid-cols-3 gap-2 sm:grid-cols-4">
                            @foreach ($gallery as $image)
                                <div class="overflow-hidden border border-line bg-concrete">
                                    <img
                                        src="{{ $image->media->url() }}"
                                        alt="{{ $image->media->alt ?: $product->name }}"
                                        class="aspect-square w-full object-cover"
                                        loading="lazy"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <p class="section-label mb-3">{{ $product->category?->name }}</p>
                    <h1 class="heading-display text-ink">{{ $product->name }}</h1>
                    @if ($product->tagline)
                        <p class="mt-4 text-xl text-steel">{{ $product->tagline }}</p>
                    @endif
                    <p class="mt-5 text-steel">{{ $product->short_description }}</p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('quote.create', ['product' => $product->id]) }}" class="btn-primary">
                            <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
                            Request Quote
                        </a>
                        @if ($product->is_comparable)
                            <a href="{{ route('products.compare', ['products' => [$product->slug]]) }}" class="btn-dark">
                                <i class="fa-solid fa-table-columns" aria-hidden="true"></i>
                                Compare
                            </a>
                        @endif
                    </div>

                    @if ($product->specifications->isNotEmpty())
                        <dl class="mt-8 grid grid-cols-2 gap-3 border border-line bg-mist p-4 sm:p-5">
                            @foreach ($product->specifications->take(4) as $spec)
                                <div>
                                    <dt class="text-xs tracking-wide text-steel uppercase">{{ $spec->label }}</dt>
                                    <dd class="mt-1 text-sm font-semibold">{{ $spec->value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="py-14">
        <div class="container-page space-y-10">
            @foreach ([
                'overview' => 'Product Overview',
                'technical_information' => 'Technical Information',
                'applications' => 'Applications',
                'key_benefits' => 'Key Benefits',
                'packaging' => 'Packaging',
                'quality_standards' => 'Quality & Standards',
            ] as $field => $heading)
                @if (filled($product->{$field}))
                    <div class="border-b border-line pb-10">
                        <h2 class="font-display text-3xl font-bold uppercase tracking-wide">{{ $heading }}</h2>
                        <div class="mt-4 max-w-3xl whitespace-pre-line text-steel">{{ $product->{$field} }}</div>
                    </div>
                @endif
            @endforeach

            @if ($product->specifications->isNotEmpty())
                <div class="border-b border-line pb-10">
                    <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Specifications</h2>
                    <div class="mt-5 overflow-x-auto border border-line bg-white">
                        <table class="min-w-full text-left text-sm">
                            <tbody>
                                @foreach ($product->specifications as $spec)
                                    <tr class="border-b border-line/70">
                                        <th class="bg-mist px-4 py-3 font-semibold text-ink">{{ $spec->label }}</th>
                                        <td class="px-4 py-3 text-steel">{{ $spec->value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($product->datasheets->isNotEmpty())
                <div class="border-b border-line pb-10">
                    <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Download Datasheet</h2>
                    <ul class="mt-4 space-y-2">
                        @foreach ($product->datasheets as $sheet)
                            <li>
                                <a href="{{ $sheet->media->url() }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-deep hover:underline" target="_blank" rel="noopener">
                                    <i class="fa-solid fa-file-pdf" aria-hidden="true"></i>
                                    {{ $sheet->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="border border-line bg-white p-6 sm:p-8">
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Need pricing for {{ $product->name }}?</h2>
                <p class="mt-3 max-w-2xl text-steel">Submit a quotation request and our sales team will respond with a reference number.</p>
                <a href="{{ route('quote.create', ['product' => $product->id]) }}" class="btn-primary mt-6">Request Quote</a>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-line bg-white py-14">
            <div class="container-page">
                <h2 class="font-display text-3xl font-bold uppercase tracking-wide">Related products</h2>
                <div class="mt-8 card-grid">
                    @foreach ($related as $item)
                        <a href="{{ route('products.show', $item) }}" class="card-tile">
                            <div class="aspect-[4/3] overflow-hidden bg-concrete">
                                <img src="{{ $item->imageUrl() }}" alt="{{ $item->imageAlt() }}" class="h-full w-full object-cover" loading="lazy">
                            </div>
                            <div class="card-tile-body">
                                <p class="card-kicker">{{ $item->category?->name }}</p>
                                <h3 class="card-title">{{ $item->name }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
