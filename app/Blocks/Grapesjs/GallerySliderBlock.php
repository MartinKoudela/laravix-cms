<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class GallerySliderBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('gallery-slider')
            ->label('blocks.gjs.gallery_slider')
            ->icon('fa-film')
            ->category('blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Gallery</h2>
        </div>
        <div class="swiper lx-gallery-slider__swiper" data-loop="true" data-per-view="auto" data-gap="16" data-centered="true">
            <div class="swiper-wrapper">
                <div class="swiper-slide lx-gallery-slider__slide"><img src="https://placehold.co/760x500?text=1" data-gjs-type="media-image" class="lx-gallery-slider__img" alt=""></div>
                <div class="swiper-slide lx-gallery-slider__slide"><img src="https://placehold.co/760x500?text=2" data-gjs-type="media-image" class="lx-gallery-slider__img" alt=""></div>
                <div class="swiper-slide lx-gallery-slider__slide"><img src="https://placehold.co/760x500?text=3" data-gjs-type="media-image" class="lx-gallery-slider__img" alt=""></div>
                <div class="swiper-slide lx-gallery-slider__slide"><img src="https://placehold.co/760x500?text=4" data-gjs-type="media-image" class="lx-gallery-slider__img" alt=""></div>
                <div class="swiper-slide lx-gallery-slider__slide"><img src="https://placehold.co/760x500?text=5" data-gjs-type="media-image" class="lx-gallery-slider__img" alt=""></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
HTML);
    }
}
