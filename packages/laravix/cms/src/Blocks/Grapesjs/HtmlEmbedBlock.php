<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class HtmlEmbedBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('html-embed-block')
            ->label('laravix::blocks.gjs.html')
            ->icon('fa-code')
            ->category('laravix::blocks.categories.elements')
            ->canvasHtml('<div data-gjs-type="html-embed" class="lx-html-embed"><!-- HTML --></div>');
    }
}
