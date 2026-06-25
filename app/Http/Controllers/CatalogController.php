<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use App\Models\CatalogImage;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class CatalogController extends Controller
{
    use PageFallback;

    public function index(): View
    {
        $pb = $this->renderFromPageBuilder('izobrazheniya');
        if ($pb) return $pb;

        $categories = Cache::remember('catalog_categories_list', 3600, fn () =>
            CatalogCategory::orderBy('sort_order')->get(['id', 'name', 'slug'])
        );

        $colors = Cache::remember('catalog_colors_list', 3600, fn () =>
            CatalogColor::orderBy('sort_order')->get(['id', 'name', 'slug', 'hex'])
        );

        return view('pages.catalog', compact('categories', 'colors'));
    }

    public function show(string $category, ?string $color = null, ?string $imageId = null): View
    {
        $cat = CatalogCategory::where('slug', $category)->firstOrFail();

        $image = null;
        if ($imageId) {
            $image = CatalogImage::where('id', $imageId)
                ->where('category_id', $cat->id)
                ->firstOrFail();
        }

        $categories = CatalogCategory::orderBy('sort_order')->get(['id', 'name', 'slug']);
        $colors = CatalogColor::orderBy('sort_order')->get(['id', 'name', 'slug', 'hex']);

        return view('pages.catalog-show', compact('cat', 'image', 'categories', 'colors'));
    }
}
