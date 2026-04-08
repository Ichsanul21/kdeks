<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaStorageService
{
    public function replace(?string $currentPath, UploadedFile $file, string $disk, string $directory): string
    {
        if ($currentPath) {
            Storage::disk($disk)->delete($currentPath);
        }

        return $file->store($directory, $disk);
    }

    public function delete(?string $path, string $disk): void
    {
        if ($path) {
            Storage::disk($disk)->delete($path);
        }
    }
}
