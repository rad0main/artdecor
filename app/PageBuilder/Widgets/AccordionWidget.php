<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;

class AccordionWidget extends BaseWidget
{
    public static function name(): string { return 'accordion'; }
    public static function title(): string { return 'Аккордеон / FAQ'; }
    public static function icon(): string { return 'heroicon-o-queue-list'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'items' => [
                ['title' => 'Какие сроки изготовления?', 'content' => 'От 7 до 14 рабочих дней.'],
                ['title' => 'Какой у вас опыт работы?', 'content' => 'Более 12 лет.'],
                ['title' => 'Какие гарантии вы даёте?', 'content' => 'От 1 до 5 лет.'],
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            \Filament\Forms\Components\Repeater::make('items')
                ->label('Вопросы')
                ->schema([
                    TextInput::make('title')->label('Вопрос')->required(),
                    RichEditor::make('content')->label('Ответ'),
                ])
                ->default(self::defaults()['items'])
                ->collapsible(),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.accordion', $settings);
    }
}
