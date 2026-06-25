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
            'content' => '<p>Введите текст</p>',
            'alignment' => 'left',
            'max_width' => 'page',
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'content', 'label' => 'Содержимое', 'type' => 'html', 'placeholder' => 'HTML-контент'],
            ['key' => 'alignment', 'label' => 'Выравнивание', 'type' => 'select', 'options' => ['left' => 'Слева', 'center' => 'По центру', 'right' => 'Справа']],
            ['key' => 'max_width', 'label' => 'Ширина', 'type' => 'select', 'options' => ['page' => 'Как у страницы', 'full' => 'На всю ширину', 'narrow' => 'Узкий']],
        ];
    }

    public static function schema(): array
    {
        return [
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
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.text', $settings);
    }
}
