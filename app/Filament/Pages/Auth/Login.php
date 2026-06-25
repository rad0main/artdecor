<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('login')
                    ->label('Логин')
                    ->required()
                    ->autocomplete()
                    ->autofocus(),
                TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function getCredentials(array $data): array
    {
        return [
            'login' => $data['login'],
            'password' => $data['password'],
        ];
    }
}
