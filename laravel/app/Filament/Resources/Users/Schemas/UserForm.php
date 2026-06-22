<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                // The User model casts `password` as "hashed", so the plain
                // value is hashed automatically on save. On edit, a blank
                // field leaves the existing password untouched.
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->maxLength(255)
                    ->helperText('Leave blank to keep the current password.'),
                Toggle::make('is_admin')
                    ->label('Administrator')
                    ->default(true)
                    ->helperText('Only administrators can sign in to this panel.'),
            ]);
    }
}
