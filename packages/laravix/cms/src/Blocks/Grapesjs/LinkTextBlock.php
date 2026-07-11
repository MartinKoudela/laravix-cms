<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class LinkTextBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('link-text')
            ->label('blocks.gjs.link')
            ->icon('fa-link')
            ->category('blocks.categories.elements')
            ->canvasHtml('<a href="#" class="lx-link">Click here</a>');
    }
}
