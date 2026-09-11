<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Enums;

enum PlaceholderReason: string
{
    case Headless = 'headless';
    case NoHomepage = 'no_homepage';
    case HomepageDraft = 'homepage_draft';
    case HomepageEmpty = 'homepage_empty';

    public function status(): int
    {
        return $this === self::Headless ? 404 : 200;
    }
}
