<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Roles;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Laravix\Cms\Filament\Resources\Roles\Pages\CreateRole;
use Laravix\Cms\Filament\Resources\Roles\Pages\EditRole;
use Laravix\Cms\Filament\Resources\Roles\Pages\ListRoles;
use Laravix\Cms\Filament\Resources\Roles\Schemas\RoleForm;
use Laravix\Cms\Filament\Resources\Roles\Tables\RolesTable;
use Laravix\Cms\Filament\Resources\Users\UserResource;
use Laravix\Cms\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Management';

    protected static ?int $navigationSort = 32;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationParentItem = UserResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('laravix::roles.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('laravix::roles.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
