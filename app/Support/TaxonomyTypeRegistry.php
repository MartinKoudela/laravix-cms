<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

class TaxonomyTypeRegistry
{
    private static array $types = [];

    public static function register(string $key, string $label): void
    {
        static::$types[$key] = $label;
    }

    public static function options(): array
    {
        return array_map(fn (string $label) => __($label), static::$types);
    }

    public static function label(string $key): string
    {
        return isset(static::$types[$key]) ? __(static::$types[$key]) : $key;
    }
}
