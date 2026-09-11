<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Services;

use Laravix\Cms\Enums\PlaceholderReason;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;

class PlaceholderResolver
{
    public function reasonFor(Site $site, ?Content $homepage): ?PlaceholderReason
    {
        if ($site->isHeadless()) {
            return PlaceholderReason::Headless;
        }

        if ($homepage === null) {
            return $site->contents()->where('is_homepage', true)->exists()
                ? PlaceholderReason::HomepageDraft
                : PlaceholderReason::NoHomepage;
        }

        if (! $homepage->hasBuilderContent()) {
            return PlaceholderReason::HomepageEmpty;
        }

        return null;
    }
}
