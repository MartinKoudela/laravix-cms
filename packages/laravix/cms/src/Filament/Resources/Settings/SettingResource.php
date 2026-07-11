<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Settings;

use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Settings\Pages\ManageSettings;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Support\SettingRegistry;

use function Filament\Support\original_request;

class SettingResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('laravix::settings.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('laravix::settings.plural');
    }

    protected static ?string $model = Setting::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

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

    public static function groupOptions(): array
    {
        $groups = [];

        foreach (array_keys(SettingRegistry::grouped()) as $group) {
            $groups[Str::afterLast($group, '.')] = $group;
        }

        if (filament()->getTenant() instanceof Site && filament()->getTenant()->isHeadless()) {
            $groups['api'] = 'laravix::settings.tabs.api';
        } else {
            $groups['appearance'] = 'laravix::settings.tabs.appearance';
        }

        return $groups;
    }

    public static function getNavigationItems(): array
    {
        $groups = static::groupOptions();
        $defaultSlug = array_key_first($groups);

        $childItems = collect($groups)
            ->map(fn (string $labelKey, string $slug) => NavigationItem::make(fn () => __($labelKey))
                ->url(fn () => static::getUrl('index', ['group' => $slug]))
                ->isActiveWhen(fn (): bool => original_request()->routeIs(static::getRouteBaseName().'.index')
                    && original_request()->query('group', $defaultSlug) === $slug))
            ->values()
            ->all();

        [$item] = parent::getNavigationItems();

        return [$item->childItems($childItems)];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSettings::route('/'),
        ];
    }
}
