<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\WorkCategory;
use Illuminate\View\View;

class WorkController extends Controller
{
    use PageFallback;

    public function index(): View
    {
        $pb = $this->renderFromPageBuilder('nashi_raboti');
        if ($pb) return $pb;

        $categories = WorkCategory::orderBy('name')
            ->get(['id', 'name', 'slug']);

        return view('pages.works', compact('categories'));
    }
}
