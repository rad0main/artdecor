<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Slide;
use App\Models\Work;
use App\Models\CatalogCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $slides = Slide::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($slide) => [
                'id' => $slide->id,
                'title' => $slide->title,
                'subtitle' => $slide->subtitle,
                'link' => $slide->link,
                'btn_text' => $slide->btn_text,
                'image' => $slide->getFirstMediaUrl('slides', 'hero'),
            ]);

        $featuredWorks = Work::where('is_featured', true)
            ->with('media')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $categories = CatalogCategory::orderBy('sort_order')
            ->get(['id', 'name', 'slug']);

        return view('pages.home', compact('slides', 'featuredWorks', 'categories'));
    }
}
