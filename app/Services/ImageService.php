<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function generateThumbnail(string $sourcePath, int $width = 100, int $height = 100): string
    {
        $sourcePath = $this->normalizePublicStoragePath($sourcePath);
        if ($sourcePath === '') {
            return '';
        }

        $fullPath = Storage::disk('public')->path($sourcePath);

        if (!file_exists($fullPath)) {
            return $sourcePath;
        }

        $directory = pathinfo($sourcePath, PATHINFO_DIRNAME);
        $filename = pathinfo($sourcePath, PATHINFO_FILENAME);
        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);

        $thumbnailFilename = $filename . '_thumb.' . $extension;
        $thumbnailPath = $directory . '/' . $thumbnailFilename;
        $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);

        $image = $this->manager->read($fullPath);
        $image->cover($width, $height);
        $image->save($thumbnailFullPath);

        return $thumbnailPath;
    }

    private function normalizePublicStoragePath(string $path): string
    {
        $path = trim($path);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parts = parse_url($path);
            $path = $parts['path'] ?? $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $path = str_replace('\\', '/', $path);

        if (str_contains($path, '../') || str_starts_with($path, '../') || str_contains($path, '/..')) {
            return '';
        }

        return $path;
    }
}
