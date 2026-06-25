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
