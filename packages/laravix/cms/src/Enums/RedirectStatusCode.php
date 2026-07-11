<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Enums;

enum RedirectStatusCode: int
{
    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;
    case TEMPORARY_REDIRECT = 307;
    case PERMANENT_REDIRECT = 308;

}
