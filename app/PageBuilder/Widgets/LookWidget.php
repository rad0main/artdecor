<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Contracts\View\View;

class LookWidget extends BaseWidget
{
    public static function name(): string { return 'look'; }
    public static function title(): string { return 'Примерка / Look'; }
    public static function icon(): string { return 'heroicon-o-cube'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'heading' => 'Примерить скинали онлайн',
            'text' => 'Выберите нужный цвет фасадов кухни, нажав на нужный цвет на представленной палитре. После этого нажмите на выбранное изображение из каталога.',
            'text_color' => '#333333',
            'text_size' => 16,
            'facade_color' => 'black',
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок', 'type' => 'text'],
            ['key' => 'text', 'label' => 'Текст описания', 'type' => 'textarea'],
            ['key' => 'text_color', 'label' => 'Цвет текста (hex)', 'type' => 'color'],
            ['key' => 'text_size', 'label' => 'Размер шрифта (px)', 'type' => 'number', 'min' => 10, 'max' => 36, 'width' => '80px'],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')
                ->label('Заголовок')
                ->default('Примерить скинали онлайн')
                ->maxLength(255),
            Textarea::make('text')
                ->label('Текст описания')
                ->maxLength(500),
            \Filament\Forms\Components\ColorPicker::make('text_color')
                ->label('Цвет текста')
                ->default('#333333'),
            \Filament\Forms\Components\TextInput::make('text_size')
                ->label('Размер шрифта (px)')
                ->numeric()
                ->minValue(10)
                ->maxValue(36)
                ->default(16)
                ->suffix('px'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.look', $settings);
    }
}
