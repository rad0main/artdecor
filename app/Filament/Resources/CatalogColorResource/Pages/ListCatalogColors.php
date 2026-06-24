<?php

namespace App\Filament\Resources\CatalogColorResource\Pages;

use App\Filament\Resources\CatalogColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatalogColors extends ListRecords
{
    protected static string $resource = CatalogColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
