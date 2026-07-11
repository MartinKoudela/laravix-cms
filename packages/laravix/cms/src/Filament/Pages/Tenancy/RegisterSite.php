<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Pages\Tenancy;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Models\Site;

class RegisterSite extends RegisterTenant
{
    public function hasLogo(): bool
    {
        return true;
    }

    public static function canView(): bool
    {
        return auth()->user()?->is_super_admin ?? false;
    }

    public static function getLabel(): string
    {
        return __('laravix::sites.actions.create');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->unique(table: 'sites', column: 'domain'),
                Select::make('mode')
                    ->label(__('laravix::sites.fields.mode'))
                    ->options([
                        SiteMode::THEME->value => __('laravix::sites.modes.theme'),
                        SiteMode::HEADLESS->value => __('laravix::sites.modes.headless'),
                    ])
                    ->default(SiteMode::THEME->value)
                    ->required()
                    ->live(),
                Select::make('theme')
                    ->label(__('laravix::common.theme'))
                    ->default('default')
                    ->options(Site::availableThemes())
                    ->visible(fn (Get $get) => $get('mode') === SiteMode::THEME->value)
                    ->required(fn (Get $get) => $get('mode') === SiteMode::THEME->value),
            ]);
    }

    protected function handleRegistration(array $data): Site
    {
        $site = Site::create($data);

        $site->users()->attach(auth()->id());

        return $site;
    }
}
