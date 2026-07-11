<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Users;

use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Users\Pages\CreateUser;
use Laravix\Cms\Filament\Resources\Users\Pages\EditUser;
use Laravix\Cms\Filament\Resources\Users\Pages\ListUsers;
use Laravix\Cms\Filament\Resources\Users\Schemas\UserForm;
use Laravix\Cms\Filament\Resources\Users\Tables\UsersTable;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

class UserResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('laravix::users.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('laravix::users.plural');
    }

    protected static ?string $model = User::class;

    protected static bool $isScopedToTenant = false;

    protected static string|null|\UnitEnum $navigationGroup = 'Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->is_super_admin) {
            return true;
        }

        $site = filament()->getTenant();

        return $site instanceof Site
            && $user->roleForSite($site) === SiteRole::ADMIN;
    }

    public static function getEloquentQuery(): Builder
    {
        $tenantId = Filament::getTenant()?->id;

        return parent::getEloquentQuery()
            ->leftJoin('site_user', function ($join) use ($tenantId) {
                $join->on('users.id', '=', 'site_user.user_id')
                    ->where('site_user.site_id', '=', $tenantId);
            })
            ->where(function (Builder $query) {
                $query->whereNotNull('site_user.site_id')
                    ->orWhere('users.is_super_admin', true);
            })
            ->select('users.*', 'site_user.role');
    }

    public static function canEdit(Model $record): bool
    {
        return ! $record->is_super_admin;
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
