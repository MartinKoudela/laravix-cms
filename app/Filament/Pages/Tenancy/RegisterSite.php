<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Pages\Tenancy;

use App\Models\Site;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

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
        return __('sites.actions.create');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->unique(table: 'sites', column: 'domain'),
                Select::make('theme')
                    ->label(__('common.theme'))
                    ->required()
                    ->default('default')
                    ->options(Site::availableThemes()),
            ]);
    }

    protected function handleRegistration(array $data): Site
    {
        $site = Site::create($data);

        $site->users()->attach(auth()->id());

        return $site;
    }
}
