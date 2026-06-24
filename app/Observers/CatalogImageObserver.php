<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\CatalogImage;
use Illuminate\Support\Facades\Cache;

class CatalogImageObserver
{
    public function saved(CatalogImage $image): void
    {
        Cache::tags(['catalog'])->flush();
    }

    public function deleted(CatalogImage $image): void
    {
        Cache::tags(['catalog'])->flush();
    }

    public function restored(CatalogImage $image): void
    {
        Cache::tags(['catalog'])->flush();
    }

    public function forceDeleted(CatalogImage $image): void
    {
        Cache::tags(['catalog'])->flush();
    }
}
