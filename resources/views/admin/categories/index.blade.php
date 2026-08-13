@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Catalogue</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Categories</h1>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">Add category</a>
    </div>

    @error('category')
        <div class="mb-6 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Parent</th>
                    <th class="px-4 py-3">Products</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3 font-semibold">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-steel">{{ $category->parent?->name ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $category->products_count }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $category->is_active ? 'text-green-700' : 'text-steel' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="font-semibold text-brand-deep hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
