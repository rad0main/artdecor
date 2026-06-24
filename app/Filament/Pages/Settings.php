<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Настройки';
    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'phone' => Setting::get('contacts.phone'),
            'email' => Setting::get('contacts.email'),
            'address' => Setting::get('contacts.address'),
            'work_hours' => Setting::get('contacts.work_hours'),
            'yandex_metrika_id' => Setting::get('integrations.yandex_metrika_id'),
            'jivosite_id' => Setting::get('integrations.jivosite_id'),
            'default_title' => Setting::get('seo.default_title'),
            'default_description' => Setting::get('seo.default_description'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Контакты')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->label('Адрес')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('work_hours')
                            ->label('Часы работы')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Интеграции')
                    ->schema([
                        Forms\Components\TextInput::make('yandex_metrika_id')
                            ->label('Яндекс.Метрика ID')
                            ->numeric(),
                        Forms\Components\TextInput::make('jivosite_id')
                            ->label('Jivosite ID')
                            ->maxLength(100),
                    ])->columns(2),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('default_title')
                            ->label('Заголовок по умолчанию')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('default_description')
                            ->label('Описание по умолчанию')
                            ->rows(3),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Настройки сохранены')
            ->success()
            ->send();
    }
}
