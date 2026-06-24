<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CatalogImage;
use App\Models\Work;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $images = CatalogImage::active()->get();
        $works = Work::all();
        $services = Service::all();

        return response()
            ->view('seo.sitemap', compact('images', 'works', 'services'), 200)
            ->header('Content-Type', 'application/xml');
    }
}
