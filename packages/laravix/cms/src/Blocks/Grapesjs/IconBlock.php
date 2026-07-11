<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class IconBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('icon-block')
            ->label('blocks.gjs.icon')
            ->icon('fa-icons')
            ->category('blocks.categories.elements')
            ->canvasHtml('<i class="fa-solid fa-star lx-icon"></i>');
    }
}
