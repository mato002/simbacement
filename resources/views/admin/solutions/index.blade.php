@extends('layouts.admin')

@section('title', 'Solutions')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Content</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Solutions</h1>
        </div>
        <a href="{{ route('admin.solutions.create') }}" class="btn-primary">Add solution</a>
    </div>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Products</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solutions as $solution)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3 font-semibold">{{ $solution->name }}</td>
                        <td class="px-4 py-3">{{ $solution->products_count }}</td>
                        <td class="px-4 py-3">{{ $solution->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.solutions.edit', $solution) }}" class="font-semibold text-brand-deep hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $solutions->links() }}</div>
@endsection
