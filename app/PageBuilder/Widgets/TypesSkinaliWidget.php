<?php

declare(strict_types=1);

namespace App\PageBuilder\Widgets;

use App\PageBuilder\BaseWidget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Illuminate\Contracts\View\View;

class TypesSkinaliWidget extends BaseWidget
{
    public static function name(): string { return 'types_skinali'; }
    public static function title(): string { return 'Виды скинали / TypesSkinali'; }
    public static function icon(): string { return 'heroicon-o-film'; }
    public static function category(): string { return 'content'; }

    public static function defaults(): array
    {
        return [
            'heading' => 'Виды скинали',
            'slides' => [
                ['image' => '/images/mainprod/skinali.jpg', 'subtitle' => 'Однотонные', 'text' => 'Классические скинали без рисунка. Более 200 цветов на выбор. Матовые, глянцевые, с текстурой.'],
                ['image' => '/images/mainprod/holst.png', 'subtitle' => 'С рисунком', 'text' => 'Фотопечать на стекле. Любые изображения, от абстракции до фотообоев. Высокое качество УФ-печати.'],
                ['image' => '/images/mainprod/kuhnya.jpeg', 'subtitle' => 'С подсветкой', 'text' => 'Светодиодная подсветка по периметру или снизу. Создаёт эффект парящего стекла. Экономичное энергопотребление.'],
                ['image' => '/images/mainprod/triplex.jpg', 'subtitle' => '3D-скинали', 'text' => 'Объёмные панели с рельефом. Различные текстуры: кирпич, камень, дерево. Толщина от 6 до 12 мм.'],
                ['image' => '/images/mainprod/ognest.jpeg', 'subtitle' => 'Термостойкие', 'text' => 'Специальное закалённое стекло для зон с высокой температурой. Устанавливаются за плитой. Выдерживают до 300°C.'],
            ],
        ];
    }

    public static function config(): array
    {
        return [
            ['key' => 'heading', 'label' => 'Заголовок секции', 'type' => 'text'],
            [
                'key' => 'slides',
                'label' => 'Слайды (до 10)',
                'type' => 'repeater',
                'fields' => [
                    ['key' => 'image', 'label' => 'Изображение (URL)', 'type' => 'url'],
                    ['key' => 'subtitle', 'label' => 'Подзаголовок', 'type' => 'text', 'maxlength' => 30],
                    ['key' => 'text', 'label' => 'Текст описания', 'type' => 'textarea', 'maxlength' => 200],
                ],
                'reorderable' => true,
            ],
        ];
    }

    public static function schema(): array
    {
        return [
            TextInput::make('heading')
                ->label('Заголовок секции'),
            Repeater::make('slides')
                ->label('Слайды')
                ->schema([
                    FileUpload::make('image')
                        ->label('Изображение (широкое, ~1200×600px)')
                        ->image()
                        ->directory('pages/types-skinali')
                        ->required()
                        ->maxSize(10240),
                    TextInput::make('subtitle')
                        ->label('Подзаголовок (до 30 симв.)')
                        ->required()
                        ->maxLength(30),
                    Textarea::make('text')
                        ->label('Текст описания')
                        ->maxLength(200)
                        ->required(),
                ])
                ->collapsible()
                ->collapsed(false)
                ->reorderable()
                ->minItems(3)
                ->maxItems(10)
                ->default(self::defaults()['slides'])
                ->addActionLabel('+ Добавить слайд'),
        ];
    }

    public function render(array $settings): View
    {
        $settings = $this->mergeSettings($settings);
        return view('page-builder.types-skinali', $settings);
    }
}
