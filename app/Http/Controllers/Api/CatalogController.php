<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use App\Models\CatalogImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->get('limit', 12), 100);

        $images = CatalogImage::active()
            ->with(['category', 'colors', 'media'])
            ->filter($request->only(['category', 'color', 'search']))
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $images->getCollection()->transform(function ($image) {
            return [
                'id' => $image->id,
                'title' => $image->title,
                'category_id' => $image->category_id,
                'category' => $image->category?->name,
                'thumb' => $image->thumb_url,
                'preview' => $image->preview_url,
                'original' => $image->original_url,
                'is_favorite' => false,
            ];
        });

        return response()->json($images);
    }

    public function categories(): JsonResponse
    {
        $data = Cache::remember('api_catalog_categories', 3600, fn () =>
            CatalogCategory::orderBy('sort_order')->get(['id', 'name', 'slug'])
        );

        return response()->json($data);
    }

    public function colors(): JsonResponse
    {
        $data = Cache::remember('api_catalog_colors', 3600, fn () =>
            CatalogColor::orderBy('sort_order')->get(['id', 'name', 'slug', 'hex'])
        );

        return response()->json($data);
    }
}
