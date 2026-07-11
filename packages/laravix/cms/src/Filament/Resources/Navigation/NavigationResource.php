<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Navigation;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Navigation\Pages\ManageNavigation;
use Laravix\Cms\Models\Site;

class NavigationResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('laravix::navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('laravix::navigation.singular');
    }

    protected static ?string $model = Site::class;

    protected static bool $isScopedToTenant = false;

    protected static string|null|\UnitEnum $navigationGroup = 'Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

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

    public static function getPages(): array
    {
        return [
            'index' => ManageNavigation::route('/'),
        ];
    }
}
