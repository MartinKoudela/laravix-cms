<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class ImageFullBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('image-block')
            ->label('blocks.gjs.image_full')
            ->icon('fa-image')
            ->category('blocks.categories.elements')
            ->canvasHtml('<img src="https://placehold.co/800x400?text=Photo" data-gjs-type="media-image" class="lx-img-full" alt="">');
    }
}
