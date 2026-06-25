<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use Illuminate\View\View;

class PrimerkaController extends Controller
{
    use PageFallback;

    public function index(): View
    {
        $pb = $this->renderFromPageBuilder('primerka');
        if ($pb) return $pb;

        $categories = CatalogCategory::orderBy('sort_order')
            ->get(['id', 'name', 'slug']);

        $colors = CatalogColor::orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'hex']);

        return view('pages.primerka', compact('categories', 'colors'));
    }
}
