<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Navigation;

use App\Enums\SiteRole;
use App\Filament\Resources\Navigation\Pages\ManageNavigation;
use App\Models\Site;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

class NavigationResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('navigation.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('navigation.singular');
    }

    protected static ?string $model = Site::class;

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
