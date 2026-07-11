<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class ImageCenteredBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('image-centered')
            ->label('blocks.gjs.image_centered')
            ->icon('fa-expand')
            ->category('blocks.categories.elements')
            ->canvasHtml('<div class="lx-img-centered-wrap"><img src="https://placehold.co/400x300?text=Photo" data-gjs-type="media-image" class="lx-img-centered" alt=""></div>');
    }
}
