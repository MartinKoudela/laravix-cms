<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

use Filament\Contracts\Plugin;

class FilamentPluginRegistry
{
    private static array $plugins = [];

    public static function register(Plugin ...$plugins): void
    {
        foreach ($plugins as $plugin) {
            static::$plugins[$plugin->getId()] = $plugin;
        }
    }

    public static function all(): array
    {
        return array_values(static::$plugins);
    }
}
