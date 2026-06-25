<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;
use Illuminate\Contracts\View\View;

class TabsWidget extends BaseWidget
{
    public static function name(): string { return 'tabs'; }
    public static function title(): string { return 'Табы / Вкладки'; }
    public static function icon(): string { return 'heroicon-o-folder'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'tabs' => [
                ['title' => 'Однотонные скинали', 'content' => 'Описание однотонных скинали'],
                ['title' => 'Скинали с рисунком', 'content' => 'Описание скинали с рисунком'],
                ['title' => '3D скинали', 'content' => 'Описание 3D скинали'],
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            \Filament\Forms\Components\Repeater::make('tabs')
                ->label('Вкладки')
                ->schema([
                    TextInput::make('title')->label('Название')->required(),
                    RichEditor::make('content')->label('Содержимое'),
                ])
                ->default(self::defaults()['tabs'])
                ->collapsible(),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.tabs', $settings);
    }
}
