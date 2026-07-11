<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms;

class Laravix
{
    public static function asset(string $file): string
    {
        $path = public_path('vendor/laravix/'.$file);
        $version = is_file($path) ? filemtime($path) : null;

        return asset('vendor/laravix/'.$file).($version ? '?v='.$version : '');
    }
}
