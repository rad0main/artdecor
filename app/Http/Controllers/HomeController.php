<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Slide;
use App\Models\Work;
use App\Models\CatalogCategory;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // If a dynamic homepage page exists, render it
        $homepage = Page::where('is_homepage', true)
            ->where('is_published', true)
            ->first();

        if ($homepage) {
            $content = $homepage->renderContent();
            return view('pages.dynamic', [
                'page' => $homepage,
                'content' => $content,
            ]);
        }

        // Fallback to the original static homepage
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
