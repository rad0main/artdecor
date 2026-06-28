<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;

class TextWidget extends BaseWidget
{
    public static function name(): string { return 'text'; }
    public static function title(): string { return 'Текст'; }
    public static function icon(): string { return 'heroicon-o-document-text'; }
    public static function category(): string { return 'basic'; }

    public static function defaults(): array
    {
        return [
            'heading' => '',
            'content' => '<p>Введите текст</p>',
            'alignment' => 'left',
            'max_width' => 'page',
            'font_size' => 16,
            'heading_font_size' => 30,
            'text_color' => '#333333',
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок (необязательно)', 'type' => 'text'],
            ['key' => 'content', 'label' => 'Содержимое', 'type' => 'html', 'placeholder' => 'HTML-контент'],
            ['key' => 'alignment', 'label' => 'Выравнивание', 'type' => 'select', 'options' => ['left' => 'Слева', 'center' => 'По центру', 'right' => 'Справа']],
            ['key' => 'max_width', 'label' => 'Ширина', 'type' => 'select', 'options' => ['page' => 'Как у страницы', 'full' => 'На всю ширину', 'narrow' => 'Узкий']],
            ['key' => 'font_size', 'label' => 'Размер шрифта текста (px)', 'type' => 'number', 'min' => 10, 'max' => 48],
            ['key' => 'heading_font_size', 'label' => 'Размер заголовка (px)', 'type' => 'number', 'min' => 16, 'max' => 72],
            ['key' => 'text_color', 'label' => 'Цвет текста (hex)', 'type' => 'color'],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')
                ->label('Заголовок (необязательно)')
                ->maxLength(255),
            RichEditor::make('content')
                ->label('Содержимое')
                ->required()
                ->toolbarButtons([
                    'bold', 'italic', 'underline', 'strike',
                    'link', 'blockquote', 'bulletList', 'orderedList',
                    'h2', 'h3', 'alignLeft', 'alignCenter', 'alignRight',
                ]),
            Select::make('alignment')
                ->label('Выравнивание')
                ->options([
                    'left' => 'Слева',
                    'center' => 'По центру',
                    'right' => 'Справа',
                ])
                ->default('left'),
            Select::make('max_width')
                ->label('Максимальная ширина')
                ->options([
                    'page' => 'Как у страницы',
                    'full' => 'На всю ширину',
                    'narrow' => 'Узкий',
                ])
                ->default('page'),
            \Filament\Forms\Components\TextInput::make('font_size')
                ->label('Размер шрифта (px)')
                ->numeric()
                ->minValue(10)
                ->maxValue(48)
                ->default(16)
                ->suffix('px'),
            \Filament\Forms\Components\TextInput::make('heading_font_size')
                ->label('Размер заголовка (px)')
                ->numeric()
                ->minValue(16)
                ->maxValue(72)
                ->default(30)
                ->suffix('px'),
            \Filament\Forms\Components\ColorPicker::make('text_color')
                ->label('Цвет текста')
                ->default('#333333'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.text', $settings);
    }
}
