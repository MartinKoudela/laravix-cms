<?php

namespace App\Filament\Resources\Sites\Schemas;

use App\Models\Site;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteForm
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
                            ->maxLength(255)
                            ->helperText('The public name of the website.'),
                        TextInput::make('domain')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('example.com')
                            ->unique(table: 'sites', column:
                                'domain', ignoreRecord: true),
                    ]),
                Section::make('Appearance')
                    ->schema([
                        Select::make('theme')
                            ->required()
                            ->default('default')
                            ->options(Site::availableThemes()),
                    ]),
            ]);
    }
}
