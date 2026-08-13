@extends('layouts.public')

@section('title', 'Compare Products — '.config('app.name'))
@section('meta_description', 'Compare Simba Cement products side by side for strength, applications, packaging and recommended use.')

@section('content')
    <section class="border-b border-line bg-white">
        <div class="container-page py-14">
            <p class="section-label mb-3">Catalogue</p>
            <h1 class="heading-display text-ink !text-5xl">Compare Products</h1>
            <p class="mt-4 max-w-2xl text-lg text-steel">Select up to four products and compare key specifications side by side.</p>
        </div>
    </section>

    <section class="py-10">
        <div class="container-page space-y-8">
            <form method="GET" action="{{ route('products.compare') }}" class="border border-line bg-white p-5">
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($comparable as $product)
                        <label class="flex items-start gap-3 border border-line px-3 py-3 text-sm {{ in_array($product->slug, $selectedSlugs, true) ? 'border-brand bg-brand/10' : '' }}">
                            <input
                                type="checkbox"
                                name="products[]"
                                value="{{ $product->slug }}"
                                @checked(in_array($product->slug, $selectedSlugs, true))
                                class="mt-1"
                            >
                            <span>
                                <span class="block font-semibold">{{ $product->name }}</span>
                                <span class="text-xs text-steel">{{ $product->category?->name }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn-primary mt-5">Compare</button>
            </form>

            @if ($selected->count() >= 2)
                <div class="overflow-x-auto border border-line bg-white">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-line bg-mist">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Attribute</th>
                                @foreach ($selected as $product)
                                    <th class="px-4 py-3">
                                        <a href="{{ route('products.show', $product) }}" class="font-display text-lg font-bold uppercase tracking-wide hover:text-brand-deep">
                                            {{ $product->name }}
                                        </a>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-line/70">
                                <th class="bg-mist px-4 py-3 font-semibold">Category</th>
                                @foreach ($selected as $product)
                                    <td class="px-4 py-3">{{ $product->category?->name }}</td>
                                @endforeach
                            </tr>
                            <tr class="border-b border-line/70">
                                <th class="bg-mist px-4 py-3 font-semibold">Tagline</th>
                                @foreach ($selected as $product)
                                    <td class="px-4 py-3">{{ $product->tagline ?: '—' }}</td>
                                @endforeach
                            </tr>
                            <tr class="border-b border-line/70">
                                <th class="bg-mist px-4 py-3 font-semibold">Unit</th>
                                @foreach ($selected as $product)
                                    <td class="px-4 py-3">{{ $product->unit }}</td>
                                @endforeach
                            </tr>
                            @foreach ($labels as $label)
                                <tr class="border-b border-line/70">
                                    <th class="bg-mist px-4 py-3 font-semibold">{{ $label }}</th>
                                    @foreach ($selected as $product)
                                        <td class="px-4 py-3">
                                            {{ $product->specifications->firstWhere('label', $label)?->value ?: '—' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr class="border-b border-line/70">
                                <th class="bg-mist px-4 py-3 font-semibold">Applications</th>
                                @foreach ($selected as $product)
                                    <td class="px-4 py-3 whitespace-pre-line">{{ $product->applications ?: '—' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="bg-mist px-4 py-3 font-semibold">Action</th>
                                @foreach ($selected as $product)
                                    <td class="px-4 py-3">
                                        <a href="{{ route('quote.create', ['product' => $product->id]) }}" class="text-sm font-semibold text-brand-deep hover:underline">Request Quote</a>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @elseif ($selected->count() === 1)
                <div class="border border-dashed border-line bg-white p-8 text-steel">
                    Select at least one more product to compare with <strong class="text-ink">{{ $selected->first()->name }}</strong>.
                </div>
            @else
                <div class="border border-dashed border-line bg-white p-8 text-steel">
                    Choose two or more products above to generate a comparison table.
                </div>
            @endif
        </div>
    </section>
@endsection
