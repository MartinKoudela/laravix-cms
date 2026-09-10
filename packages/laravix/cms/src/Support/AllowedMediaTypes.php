<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Laravix\Cms\Models\Site;

class AllowedMediaTypes
{
    public const SVG = 'image/svg+xml';

    public const SVG_SETTING_KEY = 'allow_svg_uploads';

    public const IMAGES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/avif',
    ];

    public const VIDEO = [
        'video/mp4',
        'video/webm',
        'video/quicktime',
        'video/x-msvideo',
    ];

    public const AUDIO = [
        'audio/mpeg',
        'audio/ogg',
        'audio/wav',
        'audio/mp4',
    ];

    public static function images(?Site $site = null): array
    {
        return static::allowsSvg($site)
            ? [...self::IMAGES, self::SVG]
            : self::IMAGES;
    }

    public static function imagesAndVideo(?Site $site = null): array
    {
        return [...static::images($site), ...self::VIDEO];
    }

    public static function all(?Site $site = null): array
    {
        return [...static::imagesAndVideo($site), ...self::AUDIO];
    }


    public static function allowsSvg(?Site $site = null): bool
    {
        $site ??= filament()->getTenant();

        if (!$site instanceof Site) {
            return false;
        }

        return filter_var(
            $site->settings()->where('key', self::SVG_SETTING_KEY)->value('value'),
            FILTER_VALIDATE_BOOLEAN,
        );
    }
}
