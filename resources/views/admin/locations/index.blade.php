@extends('layouts.admin')

@section('title', 'Locations')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Operations</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Locations</h1>
        </div>
        <a href="{{ route('admin.locations.create') }}" class="btn-primary">Add location</a>
    </div>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">County</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($locations as $location)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3 font-semibold">{{ $location->name }}</td>
                        <td class="px-4 py-3">{{ $location->type->label() }}</td>
                        <td class="px-4 py-3">{{ $location->county ?: '—' }}</td>
                        <td class="px-4 py-3">{{ $location->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.locations.edit', $location) }}" class="font-semibold text-brand-deep hover:underline">Edit</a>
                                <x-admin.delete-form
                                    :action="route('admin.locations.destroy', $location)"
                                    title="Delete this location?"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-steel">No locations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
