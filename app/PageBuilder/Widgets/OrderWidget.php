<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\View\View;

class OrderWidget extends BaseWidget
{
    public static function name(): string { return 'order'; }
    public static function title(): string { return 'Заявка / Order'; }
    public static function icon(): string { return 'heroicon-o-document-text'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'heading' => 'Оставить заявку',
            'heading_color' => '#1a1a2e',
            'heading_size' => 28,
            'text' => 'Заполните форму и мы свяжемся с вами в ближайшее время',
            'text_color' => '#333333',
            'text_size' => 16,
            'btn_text' => 'Отправить',
            'btn_bg_color' => '#D32F2F',
            'btn_text_color' => '#FFFFFF',
            'privacy_text' => 'Согласен с условиями обработки и хранения персональных данных',
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок', 'type' => 'text'],
            ['key' => 'heading_color', 'label' => 'Цвет заголовка (hex)', 'type' => 'color'],
            ['key' => 'heading_size', 'label' => 'Размер заголовка (px)', 'type' => 'number', 'min' => 16, 'max' => 52, 'width' => '80px'],
            ['key' => 'text', 'label' => 'Текст описания', 'type' => 'textarea'],
            ['key' => 'text_color', 'label' => 'Цвет текста (hex)', 'type' => 'color'],
            ['key' => 'text_size', 'label' => 'Размер шрифта (px)', 'type' => 'number', 'min' => 10, 'max' => 36, 'width' => '80px'],
            ['key' => 'btn_text', 'label' => 'Текст кнопки', 'type' => 'text'],
            ['key' => 'btn_bg_color', 'label' => 'Цвет кнопки (hex)', 'type' => 'color'],
            ['key' => 'btn_text_color', 'label' => 'Цвет текста кнопки (hex)', 'type' => 'color'],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')
                ->label('Заголовок')
                ->default('Оставить заявку')
                ->maxLength(255),
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
            Textarea::make('text')
                ->label('Текст описания')
                ->maxLength(500),
            ColorPicker::make('text_color')
                ->label('Цвет текста')
                ->default('#333333'),
            TextInput::make('text_size')
                ->label('Размер шрифта (px)')
                ->numeric()
                ->minValue(10)
                ->maxValue(36)
                ->default(16)
                ->suffix('px'),
            TextInput::make('btn_text')
                ->label('Текст кнопки')
                ->default('Отправить'),
            ColorPicker::make('btn_bg_color')
                ->label('Цвет кнопки')
                ->default('#D32F2F'),
            ColorPicker::make('btn_text_color')
                ->label('Цвет текста кнопки')
                ->default('#FFFFFF'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.order', $settings);
    }
}
