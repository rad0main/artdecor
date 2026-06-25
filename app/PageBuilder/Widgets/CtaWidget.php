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
    public static function title(): string { return 'Призыв к действию'; }
    public static function icon(): string { return 'heroicon-o-hand-thumb-up'; }
    public static function category(): string { return 'basic'; }

    public static function defaults(): array
    {
        return [
            'title' => 'Готовы начать?',
            'description' => '',
            'btn_text' => 'Связаться',
            'btn_url' => '/contacts',
            'background_color' => '#E1323D',
            'text_color' => '#FFFFFF',
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'title', 'label' => 'Заголовок', 'type' => 'text'],
            ['key' => 'description', 'label' => 'Описание', 'type' => 'html', 'placeholder' => 'Текст описания'],
            ['key' => 'btn_text', 'label' => 'Текст кнопки', 'type' => 'text'],
            ['key' => 'btn_url', 'label' => 'Ссылка', 'type' => 'url'],
            ['key' => 'background_color', 'label' => 'Цвет фона', 'type' => 'color'],
            ['key' => 'text_color', 'label' => 'Цвет текста', 'type' => 'color'],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('title')
                ->label('Заголовок')
                ->required(),
            RichEditor::make('description')
                ->label('Описание'),
            TextInput::make('btn_text')
                ->label('Текст кнопки'),
            TextInput::make('btn_url')
                ->label('Ссылка кнопки'),
            ColorPicker::make('background_color')
                ->label('Цвет фона')
                ->default('#E1323D'),
            ColorPicker::make('text_color')
                ->label('Цвет текста')
                ->default('#FFFFFF'),
            Select::make('width')
                ->label('Ширина')
                ->options([
                    'page' => 'Как у страницы',
                    'full' => 'На всю ширину',
                ])
                ->default('page'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.cta', $settings);
    }
}
