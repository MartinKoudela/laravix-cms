<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Http\Controllers\Api\V1\Concerns;

use Laravix\Cms\Models\Site;
use Illuminate\Http\Request;

trait ResolvesLocale
{
    private function resolveLocale(Request $request, Site $site): string
    {
        $locale = $request->query('locale');

        return in_array($locale, $site->enabledLocales(), true)
            ? $locale
            : $site->defaultLocale();
    }
}
