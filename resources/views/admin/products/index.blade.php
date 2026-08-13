@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Catalogue</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Products</h1>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">Add product</a>
    </div>

    <form method="GET" class="mb-6 flex flex-col gap-3 border border-line bg-white p-4 sm:flex-row">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or SKU" class="w-full border border-line bg-mist px-3 py-2 text-sm sm:max-w-xs">
        <select name="category" class="w-full border border-line bg-mist px-3 py-2 text-sm sm:max-w-xs">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-dark !py-2">Filter</button>
    </form>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $product->name }}</p>
                            @if ($product->is_featured)
                                <p class="text-xs text-brand-deep">Featured</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-steel">{{ $product->category?->name }}</td>
                        <td class="px-4 py-3">{{ $product->sku ?: '—' }}</td>
                        <td class="px-4 py-3">
                            {{ $product->is_active && $product->published_at ? 'Published' : ($product->is_active ? 'Draft' : 'Inactive') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="font-semibold text-brand-deep hover:underline">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-steel">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endsection
