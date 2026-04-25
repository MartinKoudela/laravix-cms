<?php

namespace App\Filament\Resources\Settings;

use App\Enums\SiteRole;
use App\Filament\Resources\Settings\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\Site;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

class SettingResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('setting');
    }

    public static function getPluralModelLabel(): string
    {
        return __('settings');
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

    public static function getPages(): array
    {
        return [
            'index' => ManageSettings::route('/'),
        ];
    }
}
