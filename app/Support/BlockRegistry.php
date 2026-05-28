<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

class BlockRegistry
{
    private static array $blocks = [];

    public static function register(BlockDefinition ...$definitions): void
    {
        foreach ($definitions as $definition) {
            static::$blocks[$definition->key] = $definition;
        }
    }

    public static function all(): array
    {
        return static::$blocks;
    }

    public static function toBlocks(): array
    {
        return array_map(
            fn (BlockDefinition $def) => $def->toBlock(),
            array_filter(static::$blocks, fn (BlockDefinition $def) => $def->schema !== [])
        );
    }

    public static function toNestableBlocks(): array
    {
        return array_map(
            fn (BlockDefinition $def) => $def->toBlock(),
            array_filter(static::$blocks, fn (BlockDefinition $def) => $def->schema !== [] && $def->nestable)
        );
    }

    public static function toGrapesBlocks(): array
    {
        return array_values(array_map(
            fn (BlockDefinition $def) => $def->toGrapesBlock(),
            array_filter(static::$blocks, fn (BlockDefinition $def) => $def->canvasHtml !== null)
        ));
    }

    public static function extractMediaIds(array $blocks): array
    {
        $ids = [];
        foreach ($blocks as $block) {
            static::collectIds($block['data'] ?? [], $ids);
        }

        return array_unique($ids);
    }

    private static function collectIds(array $data, array &$ids): void
    {
        foreach ($data as $key => $value) {
            if (str_ends_with($key, '_id') && is_numeric($value) && $value) {
                $ids[] = (int) $value;
            } elseif (is_array($value)) {
                static::collectIds($value, $ids);
            }
        }
    }
}
