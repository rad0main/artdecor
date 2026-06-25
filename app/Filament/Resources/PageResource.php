<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use App\Services\PageBuilderService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Страницы';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        /** @var \App\Services\PageBuilderService $builder */
        $builder = app(PageBuilderService::class);
        $blocks = $builder->getWidgetBlocks();

        return $form->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Основное')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Заголовок страницы')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $state, Forms\Set $set) =>
                                    $set('slug', \Illuminate\Support\Str::slug($state))
                                ),
                            Forms\Components\TextInput::make('slug')
                                ->label('URL (slug)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                            Forms\Components\Textarea::make('meta_description')
                                ->label('META-описание')
                                ->rows(2)
                                ->maxLength(65535),
                            Forms\Components\Toggle::make('is_published')
                                ->label('Опубликована')
                                ->default(false),
                            Forms\Components\Toggle::make('is_homepage')
                                ->label('Главная страница')
                                ->default(false)
                                ->helperText('Если включено, эта страница будет отображаться на главной'),
                        ])->columns(2),

                    Forms\Components\Section::make('Контент страницы')
                        ->description('Добавляйте и перетаскивайте блоки для создания страницы')
                        ->schema([
                            Forms\Components\Builder::make('content')
                                ->label('')
                                ->blocks($blocks)
                                ->collapsible()
                                ->blockNumbers(false)
                                ->addActionLabel('Добавить блок')
                                ->reorderable()
                                ->columnSpanFull(),
                        ]),
                ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->searchable()
                    ->formatStateUsing(fn (string $state) => "/{$state}"),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Опубликована')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_homepage')
                    ->label('Главная')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Изменена')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view')
                    ->label('Просмотр')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Page $record) => $record->slug ? url("/page/{$record->slug}") : '#')
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
