<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    use PageFallback;

    public function index(): View
    {
        $pb = $this->renderFromPageBuilder('uslugi');
        if ($pb) return $pb;

        $services = Service::orderBy('sort_order')->get();

        return view('pages.services', compact('services'));
    }
}
