<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                    ]),
                Section::make('Password')
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText('Leave blank to keep current password when editing.'),
                    ]),
            ]);
    }
}
