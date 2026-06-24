<?php

declare(strict_types=1);

namespace App\Filament\Resources\CatalogImageResource\Pages;

use App\Filament\Resources\CatalogImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatalogImages extends ListRecords
{
    protected static string $resource = CatalogImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
