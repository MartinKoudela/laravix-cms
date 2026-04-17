<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\SiteRole;
use Filament\Forms\Components\Select;
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
                        Select::make('role')
                            ->options(collect(SiteRole::cases())->mapWithKeys(
                                fn (SiteRole $case) => [$case->value => $case->name]
                            ))
                            ->required(),
                    ]),
            ]);
    }
}
