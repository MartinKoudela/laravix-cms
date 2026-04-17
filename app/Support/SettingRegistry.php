<?php

namespace App\Support;

class SettingRegistry
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

    public static function grouped(): array
    {
        $groups = [];

        foreach (static::$definitions as $definition) {
            $group = $definition->group ?? 'General';
            $groups[$group][] = $definition;
        }

        return $groups;
    }

    public static function find(string $key): ?SettingDefinition
    {
        return static::$definitions[$key] ?? null;
    }
}
