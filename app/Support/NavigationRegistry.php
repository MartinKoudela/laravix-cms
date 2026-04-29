<?php

namespace App\Support;

class NavigationRegistry
{
    private static array $navs = [];

    public static function register(NavigationDefinition ...$definitions): void
    {
        foreach ($definitions as $definition) {
            static::$navs[$definition->key] = $definition;
        }
    }

    public static function all(): array
    {
        return static::$navs;
    }
}
