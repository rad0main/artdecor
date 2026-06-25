<?php

declare(strict_types=1);

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('visual-editor')
                ->label('Визуальный редактор')
                ->icon('heroicon-o-eye')
                ->url(fn () => Pages\VisualEditor::getUrl(['record' => $this->record]))
                ->openUrlInNewTab()
                ->color('warning'),
            Actions\Action::make('view')
                ->label('Просмотр на сайте')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => $this->record->slug ? url("/page/{$this->record->slug}") : '#')
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
