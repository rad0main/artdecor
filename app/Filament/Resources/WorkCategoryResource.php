<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WorkCategoryResource\Pages;
use App\Models\WorkCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkCategoryResource extends Resource
{
    protected static ?string $model = WorkCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Портфолио';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('name')
                ->label('Название')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge(),

                Tables\Columns\TextColumn::make('works_count')
                    ->label('Работ')
                    ->counts('works'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkCategories::route('/'),
            'create' => Pages\CreateWorkCategory::route('/create'),
            'edit' => Pages\EditWorkCategory::route('/{record}/edit'),
        ];
    }
}
