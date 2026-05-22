<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Observers;

use App\Jobs\GenerateImageVariants;
use App\Models\Media;

class ImageTransformationObserver
{
    public function created(Media $media): void
    {
        if (str_starts_with($media->mime_type, 'image/')) {
           GenerateImageVariants::dispatch($media);
        }
    }
}
