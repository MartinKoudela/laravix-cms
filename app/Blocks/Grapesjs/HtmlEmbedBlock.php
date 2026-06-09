<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class HtmlEmbedBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('html-embed-block')
            ->label('blocks.gjs.html')
            ->icon('fa-code')
            ->category('blocks.categories.elements')
            ->canvasHtml('<div data-gjs-type="html-embed" class="lx-html-embed"><!-- HTML --></div>');
    }
}
