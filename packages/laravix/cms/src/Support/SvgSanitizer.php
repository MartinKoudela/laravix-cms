<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use enshrined\svgSanitize\Sanitizer;
use Illuminate\Support\Facades\Storage;

class SvgSanitizer
{
    private const EMPTY_DOCUMENT = '<svg xmlns="http://www.w3.org/2000/svg"></svg>';

    public function sanitizeStoredFile(string $disk, string $path): void
    {
        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return;
        }

        $storage->put($path, $this->sanitize((string) $storage->get($path)));
    }

    public function sanitize(string $svg): string
    {
        $sanitizer = new Sanitizer;
        $sanitizer->removeRemoteReferences(true);

        $clean = $sanitizer->sanitize($svg);

        return is_string($clean) ? $clean : self::EMPTY_DOCUMENT;
    }
}
