<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Services\MediaLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $media = MediaAsset::query()
            ->when($request->string('folder')->toString(), fn ($q, $folder) => $q->where('folder', $folder))
            ->when($request->string('collection')->toString(), fn ($q, $collection) => $q->where('collection', $collection))
            ->latest()
            ->paginate(24)
            ->withQueryString();

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $validated = $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,webp,gif,pdf,svg'],
            'folder' => ['nullable', 'string', 'max:80'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $folder = $validated['folder'] ?? 'general';

        foreach ($request->file('files', []) as $file) {
            $mediaLibrary->store($file, $request->user(), $folder, $validated['alt'] ?? null);
        }

        return back()->with('success', 'Media uploaded successfully.');
    }

    public function destroy(MediaAsset $mediaAsset, MediaLibraryService $mediaLibrary): RedirectResponse
    {
        $mediaLibrary->delete($mediaAsset);

        return back()->with('success', 'Media deleted.');
    }
}
