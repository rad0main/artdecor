<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogColorResource\Pages;
use App\Models\CatalogColor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CatalogColorResource extends Resource
{
    protected static ?string $model = CatalogColor::class;
    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationGroup = 'Каталог';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('name')
                ->label('Название')
                ->required()
                ->maxLength(50),

            Forms\Components\ColorPicker::make('hex')
                ->label('HEX-код')
                ->required(),

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
                Tables\Columns\ColorColumn::make('hex')
                    ->label('Цвет'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge(),

                Tables\Columns\TextColumn::make('hex')
                    ->label('HEX'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Сортировка')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogColors::route('/'),
            'create' => Pages\CreateCatalogColor::route('/create'),
            'edit' => Pages\EditCatalogColor::route('/{record}/edit'),
        ];
    }
}
