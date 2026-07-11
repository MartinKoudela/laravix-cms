<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class ButtonPrimaryBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('button-primary')
            ->label('blocks.gjs.button')
            ->icon('fa-hand-pointer')
            ->category('blocks.categories.elements')
            ->canvasHtml('<a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--dark">Button text</a>');
    }
}
