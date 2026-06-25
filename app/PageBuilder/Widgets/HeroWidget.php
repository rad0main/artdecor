<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Contracts\View\View;

class HeroWidget extends BaseWidget
{
    public static function name(): string { return 'hero'; }
    public static function title(): string { return 'Hero / Слайдер'; }
    public static function icon(): string { return 'heroicon-o-photo'; }
    public static function category(): string { return 'media'; }

    public static function defaults(): array
    {
        return [
            'title' => 'Заголовок',
            'subtitle' => '',
            'btn_text' => 'Подробнее',
            'btn_url' => '',
            'height' => 'large',
            'overlay' => true,
            'slides' => [],
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
            ['key' => 'subtitle', 'label' => 'Подзаголовок', 'type' => 'text'],
            ['key' => 'btn_text', 'label' => 'Текст кнопки', 'type' => 'text'],
            ['key' => 'btn_url', 'label' => 'Ссылка кнопки', 'type' => 'url'],
            ['key' => 'height', 'label' => 'Высота', 'type' => 'select', 'options' => ['small' => '300px', 'medium' => '500px', 'large' => '700px', 'fullscreen' => 'На весь экран']],
            ['key' => 'overlay', 'label' => 'Затемнение', 'type' => 'boolean'],
        ];
    }

    public static function schema(): array
    {
        return [
            Select::make('height')
                ->label('Высота')
                ->options([
                    'small' => 'Маленький (300px)',
                    'medium' => 'Средний (500px)',
                    'large' => 'Большой (700px)',
                    'fullscreen' => 'На весь экран',
                ])
                ->default('large'),
            Toggle::make('overlay')
                ->label('Затемнение фона')
                ->default(true),
            TextInput::make('title')
                ->label('Заголовок')
                ->required(),
            TextInput::make('subtitle')
                ->label('Подзаголовок'),
            TextInput::make('btn_text')
                ->label('Текст кнопки')
                ->default('Подробнее'),
            TextInput::make('btn_url')
                ->label('Ссылка кнопки'),
            \Filament\Forms\Components\Repeater::make('slides')
                ->label('Слайды')
                ->schema([
                    FileUpload::make('image')
                        ->label('Изображение')
                        ->image()
                        ->directory('pages/hero')
                        ->required(),
                    TextInput::make('title')->label('Заголовок'),
                    TextInput::make('subtitle')->label('Подзаголовок'),
                    TextInput::make('btn_text')->label('Текст кнопки'),
                    TextInput::make('btn_url')->label('Ссылка'),
                ])
                ->collapsible()
                ->collapsed(false)
                ->minItems(0)
                ->maxItems(10),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.hero', $settings);
    }
}
