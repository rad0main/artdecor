<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Favorite;
use Illuminate\Support\Collection;

class FavoritesService
{
    public function getFavorites(string $sessionId): Collection
    {
        return Favorite::where('session_id', $sessionId)
            ->with('image.media')
            ->get()
            ->pluck('image')
            ->map(fn ($image) => [
                'id' => $image->id,
                'title' => $image->title,
                'thumb' => $image->thumb_url,
                'preview' => $image->preview_url,
            ]);
    }

    public function toggle(int $imageId, string $sessionId): array
    {
        $favorite = Favorite::where('session_id', $sessionId)
            ->where('image_id', $imageId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return ['action' => 'removed'];
        }

        Favorite::create([
            'session_id' => $sessionId,
            'image_id' => $imageId,
        ]);

        return ['action' => 'added'];
    }
}
