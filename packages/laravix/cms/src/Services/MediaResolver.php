<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Services;

use Laravix\Cms\Models\Media;
use Illuminate\Support\Collection;

class MediaResolver
{

    public function resolve(Collection $mediaIds): Collection
    {
        return Media::whereIn('id', $mediaIds)->get()->keyBy('id');
    }
}
