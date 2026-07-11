<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class SpacerBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('spacer')
            ->label('blocks.gjs.spacer')
            ->icon('fa-up-down')
            ->category('blocks.categories.elements')
            ->canvasHtml('<div class="lx-spacer"></div>');
    }
}
