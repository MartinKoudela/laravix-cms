<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Sites\Schemas;

use App\Enums\SiteMode;
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
                    ->columns(2)
                    ->schema([
                        Select::make('mode')
                            ->label(__('sites.fields.mode'))
                            ->options([
                                SiteMode::THEME->value => __('sites.modes.theme'),
                                SiteMode::HEADLESS->value => __('sites.modes.headless'),
                            ])
                            ->default(SiteMode::THEME->value)
                            ->required(),
                        Select::make('locales')
                            ->label(__('sites.fields.locales'))
                            ->multiple()
                            ->options(Site::availableContentLocales())
                            ->helperText(__('sites.hints.locales')),
                    ]),
            ]);
    }
}
