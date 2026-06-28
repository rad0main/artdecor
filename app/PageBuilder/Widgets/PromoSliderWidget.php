<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Illuminate\Contracts\View\View;

class PromoSliderWidget extends BaseWidget
{
    public static function name(): string { return 'promo_slider'; }
    public static function title(): string { return 'ПромоСлайдер / PromoSlider'; }
    public static function icon(): string { return 'heroicon-o-photo'; }
    public static function category(): string { return 'media'; }

    public static function defaults(): array
    {
        return [
            'show_dots' => true,
            'interval' => 5.0,
            'bar_opacity' => 40,
            'bar_height' => 20,
            'slides' => [
                ['image' => '', 'title' => 'Заголовок', 'text' => 'Текст описания', 'meta' => '', 'text_color' => '#333333', 'bar_color' => '#ffffff', 'font_size' => 18, 'bold' => false, 'italic' => false],
            ],
        ];
    }

    /**
     * Fields for the inline visual editor settings modal.
     */
    public static function config(): array
    {
        return [
            ['key' => 'show_dots', 'label' => 'Точки навигации', 'type' => 'boolean', 'help' => 'Показывать точки под слайдером'],
            ['key' => 'interval', 'label' => 'Длительность анимации (сек.)', 'type' => 'number', 'min' => 0.5, 'max' => 30, 'step' => 0.1],
            ['key' => 'bar_opacity', 'label' => 'Прозрачность полосы (0–100%)', 'type' => 'range', 'min' => 0, 'max' => 100],
            ['key' => 'bar_height', 'label' => 'Высота полосы (0–50%)', 'type' => 'range', 'min' => 5, 'max' => 50],
            [
                'key' => 'slides',
                'label' => 'Слайды',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'image', 'label' => 'Изображение (URL)', 'type' => 'url'],
                    ['key' => 'title', 'label' => 'Заголовок (до 30)', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'text', 'label' => 'Текст (до 100)', 'type' => 'textarea', 'maxlength' => 100],
                    ['key' => 'meta', 'label' => 'Мета-теги', 'type' => 'text'],
                    ['key' => 'text_color', 'label' => 'Цвет текста (hex)', 'type' => 'color'],
                    ['key' => 'bar_color', 'label' => 'Цвет полосы (hex)', 'type' => 'color'],
                    ['key' => 'font_size', 'label' => 'Размер шрифта (px)', 'type' => 'number', 'min' => 8, 'max' => 48],
                    ['key' => 'bold', 'label' => 'Жирный', 'type' => 'boolean'],
                    ['key' => 'italic', 'label' => 'Курсив', 'type' => 'boolean'],
                ],
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            \Filament\Forms\Components\Toggle::make('show_dots')
                ->label('Точки навигации')
                ->default(true),
            \Filament\Forms\Components\TextInput::make('interval')
                ->label('Длительность анимации (сек.)')
                ->numeric()
                ->minValue(0.5)
                ->maxValue(30)
                ->step(0.1)
                ->default(5.0)
                ->suffix('сек'),
            \Filament\Forms\Components\TextInput::make('bar_opacity')
                ->label('Прозрачность полосы (%)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(40)
                ->suffix('%')
                ->helperText('0 = полностью прозрачная, 100 = полностью белая'),
            \Filament\Forms\Components\TextInput::make('bar_height')
                ->label('Высота полосы (%)')
                ->numeric()
                ->minValue(5)
                ->maxValue(50)
                ->default(20)
                ->suffix('%'),
            Repeater::make('slides')
                ->label('Слайды')
                ->schema([
                    FileUpload::make('image')
                        ->label('Изображение')
                        ->image()
                        ->directory('pages/promo')
                        ->required()
                        ->maxSize(5120),
                    TextInput::make('title')
                        ->label('Заголовок (до 30 симв.)')
                        ->maxLength(30)
                        ->required(),
                    TextInput::make('text')
                        ->label('Текст (до 100 симв.)')
                        ->maxLength(100)
                        ->required(),
                    TextInput::make('meta')
                        ->label('Мета-теги изображения'),
                    \Filament\Forms\Components\ColorPicker::make('text_color')
                        ->label('Цвет текста')
                        ->default('#333333'),
                    \Filament\Forms\Components\ColorPicker::make('bar_color')
                        ->label('Цвет полосы')
                        ->default('#ffffff'),
                    \Filament\Forms\Components\TextInput::make('font_size')
                        ->label('Размер шрифта (px)')
                        ->numeric()
                        ->minValue(8)
                        ->maxValue(48)
                        ->default(18),
                    \Filament\Forms\Components\Toggle::make('bold')
                        ->label('Жирный')
                        ->default(false),
                    \Filament\Forms\Components\Toggle::make('italic')
                        ->label('Курсив')
                        ->default(false),
                ])
                ->collapsible()
                ->collapsed(false)
                ->minItems(1)
                ->maxItems(20)
                ->default(self::defaults()['slides'])
                ->addActionLabel('+ Добавить слайд'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.promo-slider', $settings);
    }
}
