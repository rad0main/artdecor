<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogImageResource\Pages;
use App\Models\CatalogImage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CatalogImageResource extends Resource
{
    protected static ?string $model = CatalogImage::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Каталог';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Артикул')
                ->maxLength(255),

            Forms\Components\Select::make('category_id')
                ->label('Категория')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->native(false),

            Forms\Components\Select::make('colors')
                ->label('Цвета')
                ->multiple()
                ->relationship('colors', 'name')
                ->preload()
                ->native(false),

            Forms\Components\Toggle::make('is_active')
                ->label('Активно')
                ->default(true),

            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->label('Изображение')
                ->collection('catalog')
                ->image()
                ->imageEditor()
                ->maxSize(10240)
                ->conversion('thumb')
                ->conversion('preview')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('width')
                ->label('Ширина (px)')
                ->numeric()
                ->disabled(),

            Forms\Components\TextInput::make('height')
                ->label('Высота (px)')
                ->numeric()
                ->disabled(),

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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('catalog')
                    ->conversion('thumb')
                    ->width(80)
                    ->height(80)
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Артикул')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->sortable(),

                Tables\Columns\TextColumn::make('colors.name')
                    ->label('Цвета')
                    ->badge()
                    ->separator(', '),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Активно'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Сорт.')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name')
                    ->native(false),

                Tables\Filters\SelectFilter::make('colors')
                    ->label('Цвет')
                    ->relationship('colors', 'name')
                    ->multiple()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogImages::route('/'),
            'create' => Pages\CreateCatalogImage::route('/create'),
            'edit' => Pages\EditCatalogImage::route('/{record}/edit'),
        ];
    }
}
