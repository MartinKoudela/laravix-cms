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
                Section::make(__('common.general'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('common.title'))
                            ->required()
                            ->maxLength(255)
                            ->helperText(__('sites.messages.public_name')),
                        TextInput::make('domain')
                            ->label(__('common.domain'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder('example.com')
                            ->unique(table: 'sites', column: 'domain', ignoreRecord: true),
                    ]),
                Section::make(__('common.appearance'))
                    ->schema([
                        Select::make('theme')
                            ->label(__('common.theme'))
                            ->required()
                            ->default('default')
                            ->options(Site::availableThemes()),
                    ]),
            ]);
    }
}
