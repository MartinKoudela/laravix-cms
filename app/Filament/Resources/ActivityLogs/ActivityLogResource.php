<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Enums\SiteRole;
use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\ActivityLogs\Tables\ActivityLogsTable;
use App\Models\Site;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('activity.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('activity.plural');
    }

    protected static ?string $model = Activity::class;

    protected static bool $isScopedToTenant = false;

    protected static string|null|\UnitEnum $navigationGroup = 'Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

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
        $query = parent::getEloquentQuery();
        $site = filament()->getTenant();

        if (! $site instanceof Site) {
            return $query->whereNull('id');
        }

        return $query->where('log_name', 'site-'.
            $site->id);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }
}
