<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use App\Models\CatalogImage;
use Illuminate\Support\Collection;

class PrimerkaService
{
    public function getCategories(): Collection
    {
        return CatalogCategory::orderBy('sort_order')
            ->get(['id', 'name', 'slug']);
    }

    public function getColors(): Collection
    {
        return CatalogColor::orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'hex']);
    }

    public function getImages(int $categoryId, int $limit = 50): Collection
    {
        return CatalogImage::active()
            ->with('media')
            ->where('category_id', $categoryId)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(fn ($image) => [
                'id' => $image->id,
                'title' => $image->title,
                'thumb' => $image->thumb_url,
                'preview' => $image->preview_url,
            ]);
    }
}
