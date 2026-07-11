<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class HeroImageBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('hero-image')
            ->label('laravix::blocks.gjs.hero_image')
            ->icon('fa-image')
            ->category('laravix::blocks.categories.hero')
            ->canvasHtml(<<<'HTML'
<section class="lx-section--hero lx-section--light">
    <div class="lx-hero__inner">
        <div class="lx-hero__content">
            <h1 class="lx-hero__title">Heading with a great image</h1>
            <p class="lx-hero__text">Product or service description.</p>
            <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--dark lx-btn--lg">Start for free</a>
        </div>
        <div class="lx-hero__image-wrap">
            <img src="https://placehold.co/600x400?text=Foto" data-gjs-type="media-image" alt="">
        </div>
    </div>
</section>
HTML);
    }
}
