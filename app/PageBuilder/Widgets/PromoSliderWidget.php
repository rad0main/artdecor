<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
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
            'title' => '',
            'text' => '',
            'slides' => [
                ['image' => '', 'title' => 'Заголовок', 'text' => 'Текст описания'],
            ],
        ];
    }

    /**
     * Fields for the inline visual editor settings modal.
     * The Repeater (slides) cannot be rendered inline, so we expose
     * simple fields that affect rendering. For full slide management
     * (add/remove images), use the admin page form.
     */
    public static function config(): array
    {
        return [
            ['key' => 'title', 'label' => 'Глобальный заголовок', 'type' => 'text'],
            ['key' => 'text', 'label' => 'Глобальный текст', 'type' => 'textarea'],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('title')
                ->label('Глобальный заголовок (опционально)'),
            TextInput::make('text')
                ->label('Глобальный текст (опционально)'),
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
