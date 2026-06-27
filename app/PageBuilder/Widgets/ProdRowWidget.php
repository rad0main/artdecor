<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Illuminate\Contracts\View\View;

class ProdRowWidget extends BaseWidget
{
    public static function name(): string { return 'prod_row'; }
    public static function title(): string { return 'ProdROW'; }
    public static function icon(): string { return 'heroicon-o-squares-2x2'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        $items = [];
        for ($i = 1; $i <= 9; $i++) {
            if ($i === 8) continue;
            $items[] = [
                'image' => '',
                'title' => 'Блок ' . $i,
                'link' => '#',
            ];
        }
        return [
            'heading' => '',
            'items' => $items,
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок секции', 'type' => 'text'],
            [
                'key' => 'items',
                'label' => 'Блоки (8 шт., 9-я ячейка пустая)',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'image', 'label' => 'Изображение (370×250px)', 'type' => 'url'],
                    ['key' => 'title', 'label' => 'Заголовок (до 60)', 'type' => 'text', 'maxlength' => 60],
                    ['key' => 'link', 'label' => 'Ссылка', 'type' => 'url'],
                ],
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')
                ->label('Заголовок секции (необязательно)'),
            Repeater::make('items')
                ->label('Блоки (8 шт., схема 3×3 без центральной ячейки)')
                ->schema([
                    FileUpload::make('image')
                        ->label('Изображение (370×250px)')
                        ->image()
                        ->directory('pages/prod-row')
                        ->required()
                        ->maxSize(5120),
                    TextInput::make('title')
                        ->label('Заголовок')
                        ->required()
                        ->maxLength(60),
                    TextInput::make('link')
                        ->label('Ссылка')
                        ->default('#'),
                ])
                ->collapsible()
                ->collapsed(false)
                ->minItems(8)
                ->maxItems(8)
                ->defaultItems(8)
                ->addActionLabel('+ Добавить блок'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.prod-row', $settings);
    }
}
