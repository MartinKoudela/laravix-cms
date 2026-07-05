<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

use RuntimeException;

class ContentTypeRegistry
{
    private static array $types = [];

    public static function register(ContentTypeDefinition ...$definitions): void
    {
        foreach ($definitions as $definition) {
            static::$types[$definition->key] = $definition;
        }
    }

    public static function all(): array
    {
        return static::$types;
    }

    public static function keys(): array
    {
        return array_keys(static::$types);
    }

    public static function has(string $key): bool
    {
        return isset(static::$types[$key]);
    }

    public static function find(string $key): ?ContentTypeDefinition
    {
        return static::$types[$key] ?? null;
    }

    public static function default(): ContentTypeDefinition
    {
        return reset(static::$types) ?: throw new RuntimeException('No content types registered.');
    }

    public static function options(): array
    {
        return array_map(fn (ContentTypeDefinition $type) => __($type->label), static::$types);
    }

    public static function navigationLinkableKeys(): array
    {
        return array_keys(array_filter(
            static::$types,
            fn (ContentTypeDefinition $type) => $type->linkableInNavigation,
        ));
    }
}
