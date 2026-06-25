<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Illuminate\Contracts\View\View;

class PricesWidget extends BaseWidget
{
    public static function name(): string { return 'prices'; }
    public static function title(): string { return 'Цены / Тарифы'; }
    public static function icon(): string { return 'heroicon-o-currency-dollar'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'heading' => 'Наши цены',
            'prices' => [
                [
                    'name' => 'Эконом',
                    'price' => '4 700',
                    'unit' => '₽/м²',
                    'features' => ['Стандартное стекло 6мм', 'Однотонная печать', 'Монтаж включён', 'Гарантия 1 год'],
                    'btn_text' => 'Заказать',
                    'btn_url' => '/contacts',
                    'featured' => false,
                ],
                [
                    'name' => 'Стандарт',
                    'price' => '6 800',
                    'unit' => '₽/м²',
                    'features' => ['Закалённое стекло 6мм', 'УФ-печать любого рисунка', 'Монтаж включён', 'Защитная плёнка', 'Гарантия 3 года'],
                    'btn_text' => 'Заказать',
                    'btn_url' => '/contacts',
                    'featured' => true,
                ],
                [
                    'name' => 'Премиум',
                    'price' => '35 000',
                    'unit' => '₽/м²',
                    'features' => ['Закалённое стекло 8мм', '3D-эффект или подсветка', 'Дизайн-проект', 'Премиум монтаж', 'Гарантия 5 лет'],
                    'btn_text' => 'Заказать',
                    'btn_url' => '/contacts',
                    'featured' => false,
                ],
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')->label('Заголовок секции'),
            \Filament\Forms\Components\Repeater::make('prices')
                ->label('Тарифы')
                ->schema([
                    TextInput::make('name')->label('Название')->required(),
                    TextInput::make('price')->label('Цена')->required(),
                    TextInput::make('unit')->label('Единица')->default('₽/м²'),
                    \Filament\Forms\Components\RichEditor::make('features')
                        ->label('Что включено (каждый с новой строки)'),
                    TextInput::make('btn_text')->label('Текст кнопки')->default('Заказать'),
                    TextInput::make('btn_url')->label('Ссылка кнопки'),
                    \Filament\Forms\Components\Toggle::make('featured')->label('Выделить (рекомендуется)'),
                ])
                ->default(self::defaults()['prices'])
                ->collapsible(),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.prices', $settings);
    }
}
