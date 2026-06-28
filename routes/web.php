<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrimerkaController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RobotsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/izobrazheniya', [CatalogController::class, 'index'])->name('catalog');
Route::get('/izobrazheniya/{category}/{color?}/{imageId?}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/primerka', [PrimerkaController::class, 'index'])->name('primerka');
Route::get('/nashi_raboti', [WorkController::class, 'index'])->name('works');
Route::get('/uslugi', [ServiceController::class, 'index'])->name('services');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots');

// Clear server cache (dev helper — access after PHP file changes)
Route::get('/admin/clear-cache', function () {
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    // Clear compiled Blade views
    foreach (glob(storage_path('framework/views/*')) as $file) {
        if (is_file($file)) @unlink($file);
    }
    return response('<h2>Cache cleared</h2><p>OPcache reset, Blade cache cleared.</p><p><a href="javascript:history.back()">← Back</a></p>', 200)
        ->header('Content-Type', 'text/html');
})->middleware(['web']);

// Diagnostic: check PricesWidget config output
Route::get('/admin/debug-prices-config', function () {
    $config = \App\PageBuilder\Widgets\PricesWidget::config();
    return response('<pre>' . htmlspecialchars(json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . '</pre>', 200)
        ->header('Content-Type', 'text/html');
})->middleware(['web']);

// Dynamic pages from Page Builder (must be last to not conflict with other routes)
Route::get('/page/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');
