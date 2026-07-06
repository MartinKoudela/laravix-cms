<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Filament\Resources\ChangelogReleases\Pages;

use Filament\Resources\Pages\CreateRecord;
use Laravix\Changelog\Filament\Resources\ChangelogReleases\ChangelogReleaseResource;

class CreateChangelogRelease extends CreateRecord
{
    protected static string $resource = ChangelogReleaseResource::class;
}
