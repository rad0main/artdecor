<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;

class ImageWidget extends BaseWidget
{
    public static function name(): string { return 'image'; }
    public static function title(): string { return 'Изображение'; }
    public static function icon(): string { return 'heroicon-o-photo'; }
    public static function category(): string { return 'media'; }

    public static function defaults(): array
    {
        return [
            'image' => null,
            'alt' => '',
            'caption' => '',
            'width' => 'full',
            'rounded' => false,
            'shadow' => false,
        ];
    }

    public static function schema(): array
    {
        return [
            FileUpload::make('image')
                ->label('Изображение')
                ->image()
                ->directory('pages/images')
                ->imageResizeMode('contain')
                ->required(),
            TextInput::make('alt')
                ->label('Alt-текст (SEO)'),
            TextInput::make('caption')
                ->label('Подпись'),
            Select::make('width')
                ->label('Ширина')
                ->options([
                    'full' => 'На всю ширину',
                    'page' => 'Как у страницы',
                    'contain' => 'По размеру',
                ])
                ->default('full'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.image', $settings);
    }
}
