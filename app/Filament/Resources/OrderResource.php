<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Заказы';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Имя'),

            Forms\Components\TextInput::make('phone')
                ->label('Телефон'),

            Forms\Components\Textarea::make('message')
                ->label('Сообщение')
                ->rows(3),

            Forms\Components\Select::make('source')
                ->label('Источник')
                ->options([
                    'catalog' => 'Каталог',
                    'primerka' => 'Примерка',
                    'callback' => 'Звонок',
                    'question' => 'Вопрос',
                    'order' => 'Заявка',
                ])
                ->native(false)
                ->disabled(),

            Forms\Components\Textarea::make('article_ids')
                ->label('Артикулы')
                ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    'new' => 'Новый',
                    'contacted' => 'Обработан',
                    'closed' => 'Закрыт',
                ])
                ->native(false)
                ->required(),

            Forms\Components\Textarea::make('manager_comment')
                ->label('Комментарий менеджера')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),

                Tables\Columns\TextColumn::make('source')
                    ->label('Источник')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'catalog' => 'info',
                        'primerka' => 'success',
                        'callback' => 'warning',
                        'question' => 'gray',
                        'order' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'catalog' => 'Каталог',
                        'primerka' => 'Примерка',
                        'callback' => 'Звонок',
                        'question' => 'Вопрос',
                        'order' => 'Заявка',
                        default => $state,
                    }),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'contacted' => 'Обработан',
                        'closed' => 'Закрыт',
                    ])
                    ->extraAttributes(['class' => 'min-w-[140px]']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'contacted' => 'Обработан',
                        'closed' => 'Закрыт',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('source')
                    ->label('Источник')
                    ->options([
                        'catalog' => 'Каталог',
                        'primerka' => 'Примерка',
                        'callback' => 'Звонок',
                        'question' => 'Вопрос',
                        'order' => 'Заявка',
                    ])
                    ->native(false),

                Tables\Filters\Filter::make('created_at')
                    ->label('Дата')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['created_until'], fn($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('export')
                    ->label('CSV')
                    ->icon('heroicon-o-arrow-down-on-square')
                    ->url(fn (Order $record) => '#')
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
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
