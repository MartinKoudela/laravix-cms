<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Laravix\Cms\Enums\SiteRole;

class PermissionRegistry
{
    private static array $definitions = [];

    public static function register(array $definitions): void
    {
        foreach ($definitions as $definition) {
            static::$definitions[$definition->key] = $definition;
        }
    }

    public static function all(): array
    {
        return array_values(static::$definitions);
    }

    public static function keys(): array
    {
        return array_keys(static::$definitions);
    }

    public static function grouped(): array
    {
        $groups = [];

        foreach (static::$definitions as $definition) {
            $groups[$definition->group][] = $definition;
        }

        return $groups;
    }

    public static function find(string $key): ?PermissionDefinition
    {
        return static::$definitions[$key] ?? null;
    }

    public static function has(string $key): bool
    {
        return isset(static::$definitions[$key]);
    }

    public static function defaultsFor(SiteRole $role): array
    {
        return array_values(array_map(
            fn (PermissionDefinition $definition): string => $definition->key,
            array_filter(static::$definitions, fn (PermissionDefinition $definition): bool => $definition->isGrantedByDefaultTo($role)),
        ));
    }

    public static function flush(): void
    {
        static::$definitions = [];
    }
}
