<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WorkController;
use Illuminate\Support\Facades\Route;

Route::prefix('catalog')->group(function () {
    Route::get('/', [CatalogController::class, 'index']);
    Route::get('/categories', [CatalogController::class, 'categories']);
    Route::get('/colors', [CatalogController::class, 'colors']);
});

Route::get('/works', [WorkController::class, 'index']);

Route::prefix('favorites')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/', [FavoriteController::class, 'store']);
    Route::delete('/{imageId}', [FavoriteController::class, 'destroy']);
});

Route::post('/order', [OrderController::class, 'store']);
