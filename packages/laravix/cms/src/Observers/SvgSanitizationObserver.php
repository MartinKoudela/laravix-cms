<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Observers;

use Laravix\Cms\Models\Media;
use Laravix\Cms\Support\AllowedMediaTypes;
use Laravix\Cms\Support\SvgSanitizer;

class SvgSanitizationObserver
{
    public function __construct(
        private readonly SvgSanitizer $sanitizer,
    ) {}

    public function created(Media $media): void
    {
        if ($media->mime_type !== AllowedMediaTypes::SVG) {
            return;
        }

        $this->sanitizer->sanitizeStoredFile($media->disk, $media->path);
    }
}
