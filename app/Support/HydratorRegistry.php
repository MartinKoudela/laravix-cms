<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

class HydratorRegistry
{
    private static array $hydrators = [];

    public static function register(string ...$hydrators): void
    {
        foreach ($hydrators as $hydrator) {
            static::$hydrators[$hydrator] = $hydrator;
        }
    }

    public static function all(): array
    {
        return array_values(static::$hydrators);
    }
}
