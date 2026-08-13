<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaLibraryService
{
    public function store(UploadedFile $file, ?User $uploader = null, string $folder = 'general', ?string $alt = null): MediaAsset
    {
        $collection = str_starts_with((string) $file->getMimeType(), 'image/')
            ? 'images'
            : (str_contains((string) $file->getMimeType(), 'pdf') ? 'documents' : 'files');

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'bin';
        $filename = Str::uuid()->toString().'.'.strtolower($extension);
        $directory = trim($folder, '/');
        $path = $file->storeAs($directory, $filename, 'public');

        $width = null;
        $height = null;

        if ($collection === 'images') {
            $imageSize = @getimagesize($file->getRealPath() ?: '');
            if (is_array($imageSize)) {
                $width = $imageSize[0] ?? null;
                $height = $imageSize[1] ?? null;
            }
        }

        return MediaAsset::query()->create([
            'disk' => 'public',
            'path' => $path,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize() ?: 0,
            'width' => $width,
            'height' => $height,
            'alt' => $alt,
            'folder' => $folder,
            'collection' => $collection,
            'uploaded_by' => $uploader?->id,
        ]);
    }

    public function delete(MediaAsset $media): void
    {
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        $media->delete();
    }
}
