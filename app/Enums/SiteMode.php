<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Enums;

enum SiteMode: string
{
    case HEADLESS = 'headless';
    case THEME = 'theme';
}
