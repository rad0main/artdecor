<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Illuminate\Contracts\View\View;

class VideoWidget extends BaseWidget
{
    public static function name(): string { return 'video'; }
    public static function title(): string { return 'Видео'; }
    public static function icon(): string { return 'heroicon-o-play'; }
    public static function category(): string { return 'media'; }

    public static function defaults(): array
    {
        return [
            'title' => 'Видео',
            'description' => '',
            'video_url' => '',
            'thumbnail' => '',
            'background_color' => '#F5F5F5',
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('title')->label('Заголовок'),
            \Filament\Forms\Components\RichEditor::make('description')->label('Описание'),
            TextInput::make('video_url')->label('URL видео (YouTube)'),
            \Filament\Forms\Components\FileUpload::make('thumbnail')
                ->label('Превью')
                ->image()
                ->directory('pages/video'),
            ColorPicker::make('background_color')->label('Цвет фона')->default('#F5F5F5'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.video', $settings);
    }
}
