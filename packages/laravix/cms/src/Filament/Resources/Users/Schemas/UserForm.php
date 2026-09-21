<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Laravix\Cms\Models\Role;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('laravix::common.general'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('laravix::common.name'))
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->label(__('laravix::common.role'))
                            ->options(fn (): array => static::roleOptions())
                            ->required(),
                    ]),
            ]);
    }

    public static function roleOptions(): array
    {
        $user = auth()->user();
        $site = filament()->getTenant();

        if (! $user instanceof User || ! $site instanceof Site) {
            return [];
        }

        return Role::assignableOptions($user, $site);
    }
}
