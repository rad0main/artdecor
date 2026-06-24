<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use Illuminate\View\View;

class PrimerkaController extends Controller
{
    public function index(): View
    {
        $categories = CatalogCategory::orderBy('sort_order')
            ->get(['id', 'name', 'slug']);

        $colors = CatalogColor::orderBy('sort_order')
            ->get(['id', 'name', 'slug', 'hex']);

        return view('pages.primerka', compact('categories', 'colors'));
    }
}
