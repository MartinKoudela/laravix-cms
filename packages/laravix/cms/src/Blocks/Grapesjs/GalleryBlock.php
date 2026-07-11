<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class GalleryBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('gallery')
            ->label('laravix::blocks.gjs.gallery')
            ->icon('fa-images')
            ->category('laravix::blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Gallery</h2>
        </div>
        <div class="lx-gallery__grid">
            <img src="https://placehold.co/600x400?text=1" data-gjs-type="media-image" class="lx-gallery__img" alt="">
            <img src="https://placehold.co/600x400?text=2" data-gjs-type="media-image" class="lx-gallery__img" alt="">
            <img src="https://placehold.co/600x400?text=3" data-gjs-type="media-image" class="lx-gallery__img" alt="">
            <img src="https://placehold.co/600x400?text=4" data-gjs-type="media-image" class="lx-gallery__img" alt="">
            <img src="https://placehold.co/600x400?text=5" data-gjs-type="media-image" class="lx-gallery__img" alt="">
            <img src="https://placehold.co/600x400?text=6" data-gjs-type="media-image" class="lx-gallery__img" alt="">
        </div>
    </div>
</section>
HTML);
    }
}
