<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class LinkTextBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('link-text')
            ->label('blocks.gjs.link')
            ->icon('fa-link')
            ->category('blocks.categories.elements')
            ->canvasHtml('<a href="#" style="color:#2563eb;text-decoration:underline;font-size:1rem;cursor:pointer;">Click here</a>');
    }
}
