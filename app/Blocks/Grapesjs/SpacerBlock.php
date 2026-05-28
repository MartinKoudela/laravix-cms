<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class SpacerBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('spacer')
            ->label('blocks.gjs.spacer')
            ->icon('fa-up-down')
            ->category('blocks.categories.elements')
            ->canvasHtml('<div style="height:80px;"></div>');
    }
}
