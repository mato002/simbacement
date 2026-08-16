@extends('layouts.admin')

@section('title', 'Media Library')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Assets</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">Media Library</h1>
        </div>
    </div>

    <div class="mb-8 border border-line bg-white p-5">
        <h2 class="font-display text-xl font-bold uppercase tracking-wide">Upload</h2>
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="mt-4 grid gap-4 md:grid-cols-4">
            @csrf
            <div class="md:col-span-2">
                <label class="mb-1.5 block text-sm font-semibold">Files</label>
                <input type="file" name="files[]" multiple required accept=".jpg,.jpeg,.png,.webp,.gif,.pdf,.svg" class="w-full border border-line bg-mist px-3 py-2 text-sm">
                @error('files') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('files.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Folder</label>
                <input type="text" name="folder" value="{{ old('folder', 'general') }}" class="w-full border border-line bg-mist px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold">Alt text</label>
                <input type="text" name="alt" value="{{ old('alt') }}" class="w-full border border-line bg-mist px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-4">
                <button type="submit" class="btn-primary">Upload files</button>
            </div>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($media as $item)
            <div class="border border-line bg-white p-3">
                <div class="mb-3 flex aspect-square items-center justify-center overflow-hidden bg-mist">
                    @if (str_starts_with((string) $item->mime_type, 'image/'))
                        <img src="{{ $item->url() }}" alt="{{ $item->alt ?: $item->original_name }}" class="h-full w-full object-cover">
                    @else
                        <div class="px-4 text-center text-sm text-steel">
                            <p class="font-semibold text-ink">{{ strtoupper(pathinfo($item->filename, PATHINFO_EXTENSION)) }}</p>
                            <p class="mt-1 break-all">{{ $item->original_name }}</p>
                        </div>
                    @endif
                </div>
                <p class="truncate text-sm font-semibold">{{ $item->original_name }}</p>
                <p class="mt-1 text-xs text-steel">{{ $item->folder }} · {{ number_format($item->size / 1024, 1) }} KB</p>
                <x-admin.delete-form
                    :action="route('admin.media.destroy', $item)"
                    label="Delete"
                    title="Delete this file?"
                    text="The file will be removed from the media library."
                    class="mt-3"
                />
            </div>
        @empty
            <div class="col-span-full border border-dashed border-line bg-white p-8 text-sm text-steel">
                No media uploaded yet.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $media->links() }}
    </div>
@endsection
