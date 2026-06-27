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
        return [
            'heading' => 'Наша продукция',
            'bar_opacity' => 40,
            'items' => [
                ['image' => '/images/mainprod/triplex.jpg', 'title' => 'Триплекс', 'link' => '#'],
                ['image' => '/images/mainprod/holst.png', 'title' => 'Холст', 'link' => '#'],
                ['image' => '/images/mainprod/panno_stekl.jpeg', 'title' => 'Стеклянные панно', 'link' => '#'],
                ['image' => '/images/mainprod/dush.jpg', 'title' => 'Душевые перегородки', 'link' => '#'],
                ['image' => '/images/mainprod/kuhnya.jpeg', 'title' => 'Кухонные фартуки', 'link' => '#'],
                ['image' => '/images/mainprod/ognest.jpeg', 'title' => 'Огнеупорное стекло', 'link' => '#'],
                ['image' => '/images/mainprod/decor.jpg', 'title' => 'Декоративные панно', 'link' => '#'],
                ['image' => '/images/mainprod/dveri.jpg', 'title' => 'Стеклянные двери', 'link' => '#'],
                ['image' => '/images/mainprod/fotopechat.jpg', 'title' => 'Фотопечать на стекле', 'link' => '#'],
                ['image' => '/images/mainprod/cifra.jpg', 'title' => 'Цифровая печать', 'link' => '#'],
                ['image' => '/images/mainprod/skinali.jpg', 'title' => 'Скинали с рисунком', 'link' => '#'],
            ],
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок секции', 'type' => 'text'],
            ['key' => 'bar_opacity', 'label' => 'Прозрачность полосы (0–100%)', 'type' => 'number', 'min' => 0, 'max' => 100],
            [
                'key' => 'items',
                'label' => 'Блоки (11 шт., 12-я ячейка пустая)',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'image', 'label' => 'Изображение (370×250px)', 'type' => 'url'],
                    ['key' => 'title', 'label' => 'Заголовок (до 60)', 'type' => 'text', 'maxlength' => 60],
                    ['key' => 'link', 'label' => 'Ссылка', 'type' => 'url'],
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
            \Filament\Forms\Components\TextInput::make('bar_opacity')
                ->label('Прозрачность полосы (%)')
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(40)
                ->suffix('%')
                ->helperText('0 = полностью прозрачная, 100 = полностью белая'),
            Repeater::make('items')
                ->label('Блоки (11 шт., схема 4×3 без ячейки 11)')
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
                ->reorderable()
                ->minItems(11)
                ->maxItems(11)
                ->defaultItems(11)
                ->addActionLabel('+ Добавить блок'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.prod-row', $settings);
    }
}
