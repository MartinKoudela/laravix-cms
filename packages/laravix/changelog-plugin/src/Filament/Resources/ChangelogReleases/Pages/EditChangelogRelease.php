<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Filament\Resources\ChangelogReleases\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Laravix\Changelog\Filament\Resources\ChangelogReleases\ChangelogReleaseResource;

class EditChangelogRelease extends EditRecord
{
    protected static string $resource = ChangelogReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
