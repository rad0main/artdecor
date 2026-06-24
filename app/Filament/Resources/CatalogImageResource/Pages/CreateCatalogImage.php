<?php

declare(strict_types=1);

namespace App\Filament\Resources\CatalogImageResource\Pages;

use App\Filament\Resources\CatalogImageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCatalogImage extends CreateRecord
{
    protected static string $resource = CatalogImageResource::class;
}
