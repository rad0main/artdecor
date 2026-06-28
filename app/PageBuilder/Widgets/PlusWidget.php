<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Illuminate\Contracts\View\View;

class PlusWidget extends BaseWidget
{
    public static function name(): string { return 'plus'; }
    public static function title(): string { return 'Преимущества / Plus'; }
    public static function icon(): string { return 'heroicon-o-hand-thumb-up'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'heading' => 'Наши преимущества',
            'items' => [
                ['icon' => '/images/plus/defpl.svg', 'title' => 'Собственное производство'],
                ['icon' => '/images/plus/factor.svg', 'title' => 'Высокое качество'],
                ['icon' => '/images/plus/glass.svg', 'title' => 'Стекло высшего сорта'],
                ['icon' => '/images/plus/rubl.svg', 'title' => 'Доступные цены'],
            ],
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок секции', 'type' => 'text'],
            [
                'key' => 'items',
                'label' => 'Преимущества (до 6)',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'icon', 'label' => 'SVG-иконка (URL)', 'type' => 'url'],
                    ['key' => 'title', 'label' => 'Текст', 'type' => 'text', 'maxlength' => 40],
                ],
                'reorderable' => true,
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')
                ->label('Заголовок секции (необязательно)'),
            Repeater::make('items')
                ->label('Преимущества (2–6)')
                ->schema([
                    FileUpload::make('icon')
                        ->label('SVG-иконка')
                        ->image()
                        ->directory('pages/plus')
                        ->required()
                        ->acceptedFileTypes(['image/svg+xml'])
                        ->maxSize(5120),
                    TextInput::make('title')
                        ->label('Текст (до 40 симв.)')
                        ->required()
                        ->maxLength(40),
                ])
                ->collapsible()
                ->collapsed(false)
                ->reorderable()
                ->minItems(2)
                ->maxItems(6)
                ->default(self::defaults()['items'])
                ->addActionLabel('+ Добавить преимущество'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.plus', $settings);
    }
}
