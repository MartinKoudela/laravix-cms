<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Closure;

class RouteRegistry
{
    private static array $callbacks = [];

    public static function register(Closure ...$callbacks): void
    {
        foreach ($callbacks as $callback) {
            static::$callbacks[] = $callback;
        }
    }

    public static function apply(): void
    {
        foreach (static::$callbacks as $callback) {
            $callback();
        }
    }
}
