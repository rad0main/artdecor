<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\Textarea;
use Illuminate\Contracts\View\View;

class HtmlWidget extends BaseWidget
{
    public static function name(): string { return 'html'; }
    public static function title(): string { return 'HTML-код'; }
    public static function icon(): string { return 'heroicon-o-code-bracket'; }
    public static function category(): string { return 'advanced'; }

    public static function defaults(): array
    {
        return ['html' => '<div>Ваш HTML-код</div>'];
    }

    public static function config(): array
    {
        return [
            ['key' => 'html', 'label' => 'HTML-код', 'type' => 'html', 'placeholder' => '<div>Код без экранирования</div>'],
        ];
    }

    public static function schema(): array
    {
        return [
            Textarea::make('html')
                ->label('HTML-код')
                ->rows(10)
                ->required()
                ->helperText('Будет выведен как есть, без экранирования'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.html', $settings);
    }
}
