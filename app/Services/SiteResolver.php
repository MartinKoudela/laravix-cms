<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Services;

use App\Models\Site;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteResolver
{
    public function resolve(string $host): Site
    {
        $site = Site::where('domain', $host)->first();

        if (! $site) {
            throw new NotFoundHttpException;
        }

        return $site;
    }
}
