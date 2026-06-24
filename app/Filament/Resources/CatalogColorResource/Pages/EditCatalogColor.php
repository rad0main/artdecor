<?php

namespace App\Filament\Resources\CatalogColorResource\Pages;

use App\Filament\Resources\CatalogColorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatalogColor extends EditRecord
{
    protected static string $resource = CatalogColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
