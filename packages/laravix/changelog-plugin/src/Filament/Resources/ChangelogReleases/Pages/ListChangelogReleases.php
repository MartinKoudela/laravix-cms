<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Filament\Resources\ChangelogReleases\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Laravix\Changelog\Filament\Resources\ChangelogReleases\ChangelogReleaseResource;

class ListChangelogReleases extends ListRecords
{
    protected static string $resource = ChangelogReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('togglePage')
                ->label(fn () => $this->pageEnabled()
                    ? __('changelog::changelog.actions.page_enabled')
                    : __('changelog::changelog.actions.page_disabled'))
                ->icon(fn () => $this->pageEnabled() ? Heroicon::OutlinedGlobeAlt : Heroicon::OutlinedEyeSlash)
                ->color(fn () => $this->pageEnabled() ? 'success' : 'gray')
                ->action(function (): void {
                    $enabled = ! $this->pageEnabled();

                    Setting::updateOrCreate(
                        ['site_id' => filament()->getTenant()?->id, 'key' => 'changelog_page_enabled'],
                        ['value' => $enabled ? '1' : '0'],
                    );

                    Notification::make()
                        ->title($enabled
                            ? __('changelog::changelog.messages.page_enabled')
                            : __('changelog::changelog.messages.page_disabled'))
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }

    private function pageEnabled(): bool
    {
        return (bool) Setting::where('site_id', filament()->getTenant()?->id)
            ->where('key', 'changelog_page_enabled')
            ->value('value');
    }
}
