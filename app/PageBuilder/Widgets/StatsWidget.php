<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Illuminate\Contracts\View\View;

class StatsWidget extends BaseWidget
{
    public static function name(): string { return 'stats'; }
    public static function title(): string { return 'Статистика'; }
    public static function icon(): string { return 'heroicon-o-chart-bar'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'stats' => [
                ['number' => '12', 'label' => 'лет на рынке'],
                ['number' => '4 320+', 'label' => 'довольных клиентов'],
                ['number' => 'от 7', 'label' => 'рабочих дней'],
            ],
            'background_color' => '#FFFFFF',
        ];
    }

    public static function schema(): array
    {
        return [
            \Filament\Forms\Components\Repeater::make('stats')
                ->label('Статистика')
                ->schema([
                    TextInput::make('number')->label('Число')->required(),
                    TextInput::make('label')->label('Подпись')->required(),
                ])
                ->default(self::defaults()['stats'])
                ->collapsible(),
            ColorPicker::make('background_color')
                ->label('Цвет фона')
                ->default('#FFFFFF'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.stats', $settings);
    }
}
