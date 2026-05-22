<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Concerns;

trait TranslatesNavigationGroup
{
    public static function getNavigationGroup(): ?string
    {
        return static::$navigationGroup ? __(static::$navigationGroup) : null;
    }
}
