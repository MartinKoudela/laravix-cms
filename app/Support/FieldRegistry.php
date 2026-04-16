<?php

namespace App\Support;

class FieldRegistry
{
    private static array $global = [];

    private static array $perType = [];

    public static function content(array $definitions): void
    {
        foreach ($definitions as $definition) {
            static::$global[$definition->key] =
                $definition;
        }
    }

    public static function contentType(string $type, array $definitions): void
    {
        foreach ($definitions as $definition) {
            static::$perType[$type][$definition->key] =
                $definition;
        }
    }

    public static function forContentType(?string $type): array
    {
        $typeFields = $type ? (static::$perType[$type] ??
            []) : [];

        return array_values(array_merge(static::$global,
            $typeFields));
    }
}
