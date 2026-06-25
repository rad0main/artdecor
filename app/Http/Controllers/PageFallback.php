<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

/**
 * Trait for controllers that want to check if a Page record
 * exists in the database and render it through the page builder
 * instead of the static Blade view.
 */
trait PageFallback
{
    /**
     * Try to render a page from the page builder.
     * Returns null if no matching page found.
     */
    protected function renderFromPageBuilder(string $slug): ?View
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (! $page) {
            return null;
        }

        $content = $page->renderContent();

        return view('pages.dynamic', [
            'page' => $page,
            'content' => $content,
        ]);
    }
}
