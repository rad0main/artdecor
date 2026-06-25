<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CatalogImage;
use App\Models\CatalogCategory;
use App\Models\CatalogColor;
use App\Observers\CatalogImageObserver;
use App\Services\PageBuilderService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PageBuilderService::class, function () {
            return PageBuilderService::boot();
        });
    }

    public function boot(): void
    {
        // Register observers for cache invalidation
        CatalogImage::observe(CatalogImageObserver::class);
    }
}
