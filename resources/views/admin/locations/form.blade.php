@extends('layouts.admin')

@section('title', $location->exists ? 'Edit Location' : 'Add Location')

@section('content')
    <div class="mb-8">
        <p class="section-label mb-2">Operations</p>
        <h1 class="font-display text-4xl font-bold uppercase tracking-wide">
            {{ $location->exists ? 'Edit Location' : 'Add Location' }}
        </h1>
    </div>

    <form method="POST" action="{{ $location->exists ? route('admin.locations.update', $location) : route('admin.locations.store') }}" class="max-w-3xl space-y-5 border border-line bg-white p-6">
        @csrf
        @if ($location->exists) @method('PUT') @endif

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name', $location->name) }}" required class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Type</label>
                <select name="type" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" @selected(old('type', $location->type?->value) === $type->value)>{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-semibold">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $location->slug) }}" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
        </div>
        <div>
            <label class="mb-1.5 block text-sm font-semibold">Address</label>
            <textarea name="address" rows="3" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('address', $location->address) }}</textarea>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
            <input type="text" name="county" value="{{ old('county', $location->county) }}" placeholder="County" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <input type="text" name="phone" value="{{ old('phone', $location->phone) }}" placeholder="Phone" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <input type="email" name="email" value="{{ old('email', $location->email) }}" placeholder="Email" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <input type="number" name="sort_order" value="{{ old('sort_order', $location->sort_order) }}" placeholder="Sort order" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <input type="text" name="latitude" value="{{ old('latitude', $location->latitude) }}" placeholder="Latitude" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
            <input type="text" name="longitude" value="{{ old('longitude', $location->longitude) }}" placeholder="Longitude" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">
        </div>
        <textarea name="notes" rows="3" placeholder="Internal notes" class="w-full border border-line bg-mist px-3 py-2.5 text-sm">{{ old('notes', $location->notes) }}</textarea>
        <label class="flex items-center gap-2 text-sm font-semibold">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $location->is_active))>
            Active
        </label>
        <div class="flex gap-3">
            <button class="btn-primary">{{ $location->exists ? 'Save location' : 'Create location' }}</button>
            <a href="{{ route('admin.locations.index') }}" class="btn-dark">Back</a>
        </div>
    </form>

    @if ($location->exists)
        <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" class="mt-6" onsubmit="return confirm('Delete location?')">
            @csrf @method('DELETE')
            <button class="text-sm font-semibold text-red-700 hover:underline">Delete location</button>
        </form>
    @endif
@endsection
