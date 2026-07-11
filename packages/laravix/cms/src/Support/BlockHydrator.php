<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;

interface BlockHydrator
{
    public function hydrate(string $html, Site $site, Content $content): string;
}
