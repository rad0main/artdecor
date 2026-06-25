<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;

class ColumnsWidget extends BaseWidget
{
    public static function name(): string { return 'columns'; }
    public static function title(): string { return 'Колонки'; }
    public static function icon(): string { return 'heroicon-o-view-columns'; }
    public static function category(): string { return 'layout'; }
    public static function isContainer(): bool { return true; }

    public static function defaults(): array
    {
        return [
            'columns_count' => 2,
            'columns' => [],
            '_children' => '',
        ];
    }

    public static function schema(): array
    {
        return [
            Select::make('columns_count')
                ->label('Количество колонок')
                ->options([2 => 2, 3 => 3, 4 => 4])
                ->default(2)
                ->reactive()
                ->afterStateUpdated(fn (callable $set, $state) => null),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.columns', $settings);
    }
}
