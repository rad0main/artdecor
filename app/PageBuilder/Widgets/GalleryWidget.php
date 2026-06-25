<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;

class GalleryWidget extends BaseWidget
{
    public static function name(): string { return 'gallery'; }
    public static function title(): string { return 'Галерея'; }
    public static function icon(): string { return 'heroicon-o-view-columns'; }
    public static function category(): string { return 'media'; }

    public static function defaults(): array
    {
        return [
            'images' => [],
            'columns' => 3,
            'gap' => 'md',
            'lightbox' => true,
        ];
    }

    public static function schema(): array
    {
        return [
            Repeater::make('images')
                ->label('Изображения')
                ->schema([
                    FileUpload::make('image')
                        ->label('Файл')
                        ->image()
                        ->directory('pages/gallery')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('caption')
                        ->label('Подпись'),
                ])
                ->collapsible()
                ->collapsed(false)
                ->defaultItems(4),
            Select::make('columns')
                ->label('Колонок')
                ->options([1 => 1, 2 => 2, 3 => 3, 4 => 4])
                ->default(3),
            Select::make('gap')
                ->label('Отступ')
                ->options([
                    'sm' => 'Маленький',
                    'md' => 'Средний',
                    'lg' => 'Большой',
                ])
                ->default('md'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.gallery', $settings);
    }
}
