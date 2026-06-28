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
            'slides' => [
                ['image' => '', 'title' => 'Заголовок', 'text' => 'Текст описания'],
            ],
        ];
    }

    /**
     * Fields for the inline visual editor settings modal.
     * Uses 'repeater' type — the visual editor must support it.
     */
    public static function config(): array
    {
        return [
            [
                'key' => 'slides',
                'label' => 'Слайды',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'image', 'label' => 'Изображение (URL)', 'type' => 'url'],
                    ['key' => 'title', 'label' => 'Заголовок (до 30)', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'text', 'label' => 'Текст (до 100)', 'type' => 'textarea', 'maxlength' => 100],
                ],
            ],
        ];
    }

    public static function schema(): array
    {
        return [
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
