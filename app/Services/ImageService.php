<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageService
{
    public function getImageDimensions(string $path): array
    {
        $size = @getimagesize($path);
        if ($size) {
            return ['width' => $size[0], 'height' => $size[1]];
        }
        return ['width' => null, 'height' => null];
    }

    public function getFileHash(string $path): string
    {
        return hash_file('sha256', $path);
    }

    public function getExtension(string $path): string
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    public function isAllowedExtension(string $ext): bool
    {
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp']);
    }
}
