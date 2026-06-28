<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
            'heading_color' => '#1a1a2e',
            'heading_size' => 28,
            'name_color' => '#1a1a2e',
            'name_size' => 20,
            'price_color' => '#D32F2F',
            'price_size' => 28,
            'features_color' => '#555555',
            'features_size' => 14,
            'unit_text_color' => '#D32F2F',
            'unit_text_size' => 16,
            'btn_text_color' => '#ffffff',
            'btn_text_size' => 14,
            'prices' => [
                [
                    'name' => 'Эконом',
                    'price' => '4 700',
                    'unit' => '₽/м²',
                    'features' => "Стандартное стекло 6мм\nОднотонная печать\nМонтаж включён\nГарантия 1 год",
                    'btn_text' => 'Заказать',
                    'featured' => false,
                ],
                [
                    'name' => 'Стандарт',
                    'price' => '6 800',
                    'unit' => '₽/м²',
                    'features' => "Закалённое стекло 6мм\nУФ-печать любого рисунка\nМонтаж включён\nЗащитная плёнка\nГарантия 3 года",
                    'btn_text' => 'Заказать',
                    'featured' => true,
                ],
                [
                    'name' => 'Премиум',
                    'price' => '35 000',
                    'unit' => '₽/м²',
                    'features' => "Закалённое стекло 8мм\n3D-эффект или подсветка\nДизайн-проект\nПремиум монтаж\nГарантия 5 лет",
                    'btn_text' => 'Заказать',
                    'featured' => false,
                ],
            ],
        ];
    }

    public static function config(): array
    {
        return [
            // ─── Текстовый контент (редактируется первым) ─────
            ['key' => 'heading', 'label' => 'Заголовок секции', 'type' => 'text'],

            // ─── Тарифы (повторяемые карточки) ─────────────────
            ['key' => 'prices', 'label' => 'Тарифы', 'type' => 'repeater', 'fields' => [
                ['key' => 'name', 'label' => 'Название тарифа', 'type' => 'text'],
                ['key' => 'price', 'label' => 'Цена', 'type' => 'text'],
                ['key' => 'unit', 'label' => 'Единица (₽/м²)', 'type' => 'text'],
                ['key' => 'features', 'label' => 'Что включено (каждая строка — новый пункт)', 'type' => 'textarea'],
                ['key' => 'btn_text', 'label' => 'Текст кнопки', 'type' => 'text'],
                ['key' => 'featured', 'label' => 'Выделить (рекомендуемый)', 'type' => 'boolean'],
            ]],

            // ─── Стилизация заголовка ─────────────────────────
            ['key' => 'heading_color', 'label' => 'Цвет заголовка', 'type' => 'color'],
            ['key' => 'heading_size', 'label' => 'Размер заголовка (px)', 'type' => 'number', 'min' => 16, 'max' => 52, 'width' => '80px'],

            // ─── Стилизация названия тарифа ───────────────────
            ['key' => 'name_color', 'label' => 'Цвет названия тарифа', 'type' => 'color'],
            ['key' => 'name_size', 'label' => 'Размер названия (px)', 'type' => 'number', 'min' => 14, 'max' => 32, 'width' => '80px'],

            // ─── Стилизация цены ─────────────────────────────
            ['key' => 'price_color', 'label' => 'Цвет цены', 'type' => 'color'],
            ['key' => 'price_size', 'label' => 'Размер цены (px)', 'type' => 'number', 'min' => 18, 'max' => 48, 'width' => '80px'],

            // ─── Стилизация единицы измерения ────────────────
            ['key' => 'unit_text_color', 'label' => 'Цвет единицы (₽/м²)', 'type' => 'color'],
            ['key' => 'unit_text_size', 'label' => 'Размер единицы (px)', 'type' => 'number', 'min' => 10, 'max' => 24, 'width' => '80px'],

            // ─── Стилизация списка опций ─────────────────────
            ['key' => 'features_color', 'label' => 'Цвет списка опций', 'type' => 'color'],
            ['key' => 'features_size', 'label' => 'Размер списка (px)', 'type' => 'number', 'min' => 10, 'max' => 20, 'width' => '80px'],

            // ─── Стилизация кнопки ───────────────────────────
            ['key' => 'btn_text_color', 'label' => 'Цвет текста кнопки', 'type' => 'color'],
            ['key' => 'btn_text_size', 'label' => 'Размер текста кнопки (px)', 'type' => 'number', 'min' => 12, 'max' => 24, 'width' => '80px'],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')
                ->label('Заголовок секции'),
            ColorPicker::make('heading_color')
                ->label('Цвет заголовка')
                ->default('#1a1a2e'),
            TextInput::make('heading_size')
                ->label('Размер заголовка (px)')
                ->numeric()
                ->minValue(16)
                ->maxValue(52)
                ->default(28)
                ->suffix('px'),

            \Filament\Forms\Components\Fieldset::make('Оформление карточек')
                ->schema([
                    ColorPicker::make('name_color')
                        ->label('Цвет названия тарифа')
                        ->default('#1a1a2e'),
                    TextInput::make('name_size')
                        ->label('Размер названия (px)')
                        ->numeric()->minValue(14)->maxValue(32)->default(20)->suffix('px'),
                    ColorPicker::make('price_color')
                        ->label('Цвет цены')
                        ->default('#D32F2F'),
                    TextInput::make('price_size')
                        ->label('Размер цены (px)')
                        ->numeric()->minValue(18)->maxValue(48)->default(28)->suffix('px'),
                    ColorPicker::make('unit_text_color')
                        ->label('Цвет единицы')
                        ->default('#D32F2F'),
                    TextInput::make('unit_text_size')
                        ->label('Размер единицы (px)')
                        ->numeric()->minValue(10)->maxValue(24)->default(16)->suffix('px'),
                    ColorPicker::make('features_color')
                        ->label('Цвет списка')
                        ->default('#555555'),
                    TextInput::make('features_size')
                        ->label('Размер списка (px)')
                        ->numeric()->minValue(10)->maxValue(20)->default(14)->suffix('px'),
                    ColorPicker::make('btn_text_color')
                        ->label('Цвет текста кнопки')
                        ->default('#ffffff'),
                    TextInput::make('btn_text_size')
                        ->label('Размер текста кнопки (px)')
                        ->numeric()->minValue(12)->maxValue(24)->default(14)->suffix('px'),
                ])->columns(2),

            \Filament\Forms\Components\Repeater::make('prices')
                ->label('Тарифы')
                ->schema([
                    TextInput::make('name')->label('Название')->required(),
                    TextInput::make('price')->label('Цена')->required(),
                    TextInput::make('unit')->label('Единица')->default('₽/м²'),
                    \Filament\Forms\Components\Textarea::make('features')
                        ->label('Что включено (каждая опция с новой строки)')
                        ->rows(5),
                    TextInput::make('btn_text')->label('Текст кнопки')->default('Заказать'),
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
