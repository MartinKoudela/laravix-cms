<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

use App\Models\ContentTypeField;
use Illuminate\Support\Facades\Cache;

class FieldRegistry
{
    private static array $global = [];

    private static array $perType = [];

    public static function content(array $definitions): void
    {
        foreach ($definitions as $definition) {
            static::$global[$definition->key] = $definition;
        }
    }

    public static function contentType(string $type, array $definitions): void
    {
        foreach ($definitions as $definition) {
            static::$perType[$type][$definition->key] = $definition;
        }
    }

    public static function forContentType(?string $type, ?int $siteId = null): array
    {
        $typeFields = $type ? (static::$perType[$type] ?? []) : [];
        $systemFields = array_merge(static::$global, $typeFields);

        if (! $siteId || ! $type) {
            return array_values($systemFields);
        }

        $userFields = Cache::remember("field_registry:{$siteId}:{$type}", 3600, function () use ($siteId, $type) {
            return ContentTypeField::where('site_id', $siteId)
                ->where('content_type', $type)
                ->orderBy('sort_order')
                ->get()
                ->mapWithKeys(fn (ContentTypeField $field) => [
                    $field->key => FieldDefinition::make($field->key)
                        ->type($field->type)
                        ->label($field->label)
                        ->group($field->group ?? 'content.sections.content')
                        ->hint($field->hint ?? '')
                        ->config($field->config ?? []),
                ])
                ->all();
        });

        $systemKeys = array_keys($systemFields);
        $filteredUserFields = array_filter($userFields, fn ($key) => ! in_array($key, $systemKeys), ARRAY_FILTER_USE_KEY);

        return array_values(array_merge($systemFields, $filteredUserFields));
    }

    public static function grouped(?string $type = null, string $defaultGroup = 'Content', ?int $siteId = null): array
    {
        $groups = [];

        foreach (static::forContentType($type, $siteId) as $definition) {
            $group = $definition->group ?? $defaultGroup;
            $groups[$group][] = $definition;
        }

        return $groups;
    }
}
