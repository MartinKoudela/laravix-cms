<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Enums;

enum TableLayout: string
{
    case List = 'list';
    case Grid = 'grid';

    public function toggle(): self
    {
        return $this === self::List ? self::Grid : self::List;
    }
}
