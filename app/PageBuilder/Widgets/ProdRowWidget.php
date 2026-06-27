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
            'items' => [
                ['image' => '/images/mainprod/триплекс.jpg', 'title' => 'Триплекс', 'link' => '#'],
                ['image' => '/images/mainprod/holst1.jpeg', 'title' => 'Холст', 'link' => '#'],
                ['image' => '/images/mainprod/z1.jpeg', 'title' => 'Стеклянные панно', 'link' => '#'],
                ['image' => '/images/mainprod/душ124.jpeg', 'title' => 'Душевые перегородки', 'link' => '#'],
                ['image' => '/images/mainprod/кпкппк-scaled.jpg', 'title' => 'Кухонные фартуки', 'link' => '#'],
                ['image' => '/images/mainprod/огл-scaled.jpg', 'title' => 'Огнеупорное стекло', 'link' => '#'],
                ['image' => '/images/mainprod/плоадлгш-scaled.jpg', 'title' => 'Декоративные панно', 'link' => '#'],
                ['image' => '/images/mainprod/уекпепе-scaled.jpg', 'title' => 'Стеклянные двери', 'link' => '#'],
                ['image' => '/images/mainprod/5jxOC9W6yGZ6oQrHEmfK-WtIbOrfoslaLYKjdWwovQRU7ZSfWGqIPVqTmWGleYawHaUVPtnUUNhjJ5-0aFEcLPdr.jpg', 'title' => 'Фотопечать на стекле', 'link' => '#'],
                ['image' => '/images/mainprod/Bez-imeni-2-2.png', 'title' => 'Цифровая печать', 'link' => '#'],
                ['image' => '/images/mainprod/image 6.1 (1).jpg', 'title' => 'Скинали с рисунком', 'link' => '#'],
            ],
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок секции', 'type' => 'text'],
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
