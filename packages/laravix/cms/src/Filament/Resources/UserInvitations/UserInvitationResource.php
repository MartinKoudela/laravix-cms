<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\UserInvitations;

use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\UserInvitations\Pages\ListUserInvitations;
use Laravix\Cms\Filament\Resources\UserInvitations\Schemas\UserInvitationForm;
use Laravix\Cms\Filament\Resources\UserInvitations\Tables\UserInvitationsTable;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\UserInvitation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserInvitationResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('invitations.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('invitations.plural');
    }

    protected static ?string $model = UserInvitation::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $recordTitleAttribute = 'email';

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

    public static function form(Schema $schema): Schema
    {
        return UserInvitationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserInvitationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserInvitations::route('/'),
        ];
    }
}
