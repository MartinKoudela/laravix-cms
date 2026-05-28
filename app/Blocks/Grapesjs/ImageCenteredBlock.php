<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class ImageCenteredBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('image-centered')
            ->label('blocks.gjs.image_centered')
            ->icon('fa-expand')
            ->category('blocks.categories.elements')
            ->canvasHtml('<div style="display:flex;justify-content:center;"><img src="https://placehold.co/400x300?text=Photo" data-gjs-type="media-image" style="width:400px;max-width:100%;height:auto;display:block;border-radius:8px;" alt=""></div>');
    }
}
