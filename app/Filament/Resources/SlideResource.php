<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\SlideResource\Pages;
use App\Models\Slide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SlideResource extends Resource
{
    protected static ?string $model = Slide::class;
    protected static ?string $navigationIcon = 'heroicon-o-window';
    protected static ?string $navigationGroup = 'Настройки';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Заголовок')
                ->maxLength(255),

            Forms\Components\TextInput::make('subtitle')
                ->label('Подзаголовок')
                ->maxLength(255),

            Forms\Components\TextInput::make('link')
                ->label('Ссылка')
                ->maxLength(500),

            Forms\Components\TextInput::make('btn_text')
                ->label('Текст кнопки')
                ->maxLength(100),

            Forms\Components\SpatieMediaLibraryFileUpload::make('slide_image')
                ->label('Изображение слайда')
                ->collection('slides')
                ->image()
                ->imageEditor()
                ->maxSize(10240)
                ->conversion('hero')
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_active')
                ->label('Активно')
                ->default(true),

            Forms\Components\TextInput::make('sort_order')
                ->label('Сортировка')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('slide_image')
                    ->collection('slides')
                    ->conversion('hero')
                    ->width(120)
                    ->height(60)
                    ->square(false),

                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Активно'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Сортировка')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSlides::route('/'),
            'create' => Pages\CreateSlide::route('/create'),
            'edit' => Pages\EditSlide::route('/{record}/edit'),
        ];
    }
}
