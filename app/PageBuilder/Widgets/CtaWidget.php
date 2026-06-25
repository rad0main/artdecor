<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;

class CtaWidget extends BaseWidget
{
    public static function name(): string { return 'cta'; }
    public static function title(): string { return 'Блок с кнопкой (CTA)'; }
    public static function icon(): string { return 'heroicon-o-hand-thumb-up'; }
    public static function category(): string { return 'basic'; }

    public static function defaults(): array
    {
        return [
            'title' => 'Готовы начать?',
            'description' => '',
            'btn_text' => 'Связаться',
            'btn_url' => '/contacts',
            'background_color' => '#D32F2F',
            'text_color' => '#FFFFFF',
            'padding' => 'py-16 md:py-20',
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
            ['key' => 'description', 'label' => 'Описание', 'type' => 'html'],
            ['key' => 'btn_text', 'label' => 'Текст кнопки', 'type' => 'text'],
            ['key' => 'btn_url', 'label' => 'Ссылка', 'type' => 'url'],
            ['key' => 'background_color', 'label' => 'Цвет фона', 'type' => 'color'],
            ['key' => 'text_color', 'label' => 'Цвет текста', 'type' => 'color'],
            ['key' => 'padding', 'label' => 'Отступы', 'type' => 'select', 'options' => ['py-8' => 'Маленькие', 'py-16 md:py-20' => 'Средние', 'py-24 md:py-32' => 'Большие']],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('title')
                ->label('Заголовок')
                ->required(),
            RichEditor::make('description')
                ->label('Описание')
                ->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList']),
            TextInput::make('btn_text')
                ->label('Текст кнопки')
                ->default('Подробнее'),
            TextInput::make('btn_url')
                ->label('Ссылка кнопки'),
            ColorPicker::make('background_color')
                ->label('Цвет фона')
                ->default('#D32F2F'),
            ColorPicker::make('text_color')
                ->label('Цвет текста')
                ->default('#FFFFFF'),
            Select::make('width')
                ->label('Ширина')
                ->options(['page' => 'Как у страницы', 'full' => 'На всю ширину'])
                ->default('page'),
            Select::make('padding')
                ->label('Отступы сверху/снизу')
                ->options([
                    'py-8' => 'Маленькие',
                    'py-12 md:py-16' => 'Средние',
                    'py-16 md:py-20' => 'Большие (по умолчанию)',
                    'py-24 md:py-32' => 'Очень большие',
                ])
                ->default('py-16 md:py-20'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.cta', $settings);
    }
}
