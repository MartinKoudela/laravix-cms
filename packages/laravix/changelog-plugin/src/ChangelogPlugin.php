<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Laravix\Changelog\Filament\Resources\ChangelogReleases\ChangelogReleaseResource;

class ChangelogPlugin implements Plugin
{
    public static function make(): static
    {
        return new static;
    }

    public function getId(): string
    {
        return 'laravix-changelog';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            ChangelogReleaseResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
