<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;

class TestimonialsWidget extends BaseWidget
{
    public static function name(): string { return 'testimonials'; }
    public static function title(): string { return 'Отзывы'; }
    public static function icon(): string { return 'heroicon-o-chat-bubble-left-right'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'items' => [
                ['text' => 'Отличная компания! Сделали скинали точно в срок.', 'author' => 'Алексей М.'],
                ['text' => 'Очень доволен результатом. Скинали с подсветкой смотрятся потрясающе.', 'author' => 'Елена К.'],
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            \Filament\Forms\Components\Repeater::make('items')
                ->label('Отзывы')
                ->schema([
                    RichEditor::make('text')->label('Текст отзыва')->required(),
                    TextInput::make('author')->label('Автор'),
                ])
                ->default(self::defaults()['items'])
                ->collapsible(),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.testimonials', $settings);
    }
}
