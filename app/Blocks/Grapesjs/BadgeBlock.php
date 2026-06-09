<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class BadgeBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('badge')
            ->label('blocks.gjs.badge')
            ->icon('fa-certificate')
            ->category('blocks.categories.elements')
            ->canvasHtml('<span class="lx-badge">New</span>');
    }
}
