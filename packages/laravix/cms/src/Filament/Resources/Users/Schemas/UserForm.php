<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Users\Schemas;

use Laravix\Cms\Enums\SiteRole;
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
                Section::make(__('common.general'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255),
                        Select::make('role')
                            ->label(__('common.role'))
                            ->options(collect(SiteRole::cases())->mapWithKeys(
                                fn (SiteRole $case) => [$case->value => $case->name]
                            ))
                            ->required(),
                    ]),
            ]);
    }
}
