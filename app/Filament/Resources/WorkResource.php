<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\WorkResource\Pages;
use App\Models\Work;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkResource extends Resource
{
    protected static ?string $model = Work::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Портфолио';
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Название')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('category_id')
                ->label('Категория')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->native(false),

            Forms\Components\RichEditor::make('description')
                ->label('Описание')
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_featured')
                ->label('Показывать на главной'),

            Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                ->label('Изображение')
                ->collection('works')
                ->image()
                ->imageEditor()
                ->maxSize(10240)
                ->conversion('thumb')
                ->conversion('preview')
                ->columnSpanFull(),

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
                    ->collection('works')
                    ->conversion('thumb')
                    ->width(80)
                    ->height(60)
                    ->square(false),

                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Главная')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Категория')
                    ->relationship('category', 'name')
                    ->native(false),
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
            'index' => Pages\ListWorks::route('/'),
            'create' => Pages\CreateWork::route('/create'),
            'edit' => Pages\EditWork::route('/{record}/edit'),
        ];
    }
}
