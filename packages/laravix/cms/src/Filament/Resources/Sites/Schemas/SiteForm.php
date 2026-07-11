<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Sites\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Models\Site;

class SiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('laravix::common.general'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('laravix::common.title'))
                            ->required()
                            ->maxLength(255)
                            ->helperText(__('laravix::sites.messages.public_name')),
                        TextInput::make('domain')
                            ->label(__('laravix::common.domain'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder('example.com')
                            ->unique(table: 'sites', column: 'domain', ignoreRecord: true),
                    ]),
                Section::make(__('laravix::common.appearance'))
                    ->columns(2)
                    ->schema([
                        Select::make('mode')
                            ->label(__('laravix::sites.fields.mode'))
                            ->options([
                                SiteMode::THEME->value => __('laravix::sites.modes.theme'),
                                SiteMode::HEADLESS->value => __('laravix::sites.modes.headless'),
                            ])
                            ->default(SiteMode::THEME->value)
                            ->required(),
                        Select::make('locales')
                            ->label(__('laravix::sites.fields.locales'))
                            ->multiple()
                            ->options(Site::availableContentLocales())
                            ->helperText(__('laravix::sites.hints.locales')),
                    ]),
            ]);
    }
}
