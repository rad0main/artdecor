<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct()
    {
        if (! app()->bound(\App\Services\PageBuilderService::class)) {
            app()->singleton(\App\Services\PageBuilderService::class, function () {
                return \App\Services\PageBuilderService::boot();
            });
        }
    }

    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $content = $page->renderContent();

        return view('pages.dynamic', [
            'page' => $page,
            'content' => $content,
        ]);
    }
}
