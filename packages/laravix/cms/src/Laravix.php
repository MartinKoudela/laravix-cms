<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms;

use Composer\InstalledVersions;
use OutOfBoundsException;

class Laravix
{
    public static function version(): string
    {
        try {
            return InstalledVersions::getPrettyVersion('laravix/cms') ?? 'dev';
        } catch (OutOfBoundsException) {
            return 'dev';
        }
    }

    public static function asset(string $file): string
    {
        $version = static::version();

        if (str_starts_with($version, 'dev')) {
            $path = public_path('vendor/laravix/'.$file);
            $version = is_file($path) ? (string) filemtime($path) : null;
        }

        return asset('vendor/laravix/'.$file).($version ? '?v='.$version : '');
    }
}
