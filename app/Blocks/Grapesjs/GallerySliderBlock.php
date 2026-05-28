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
<section style="padding:64px 24px;">
    <div style="max-width:1100px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Gallery</h2>
        <div class="swiper" data-loop="true" data-per-view="auto" data-gap="16" data-centered="true" style="overflow:hidden;padding-bottom:44px;">
            <div class="swiper-wrapper">
                <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=1" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=2" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=3" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=4" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
                <div class="swiper-slide" style="width:380px;"><img src="https://placehold.co/760x500?text=5" data-gjs-type="media-image" style="width:100%;height:260px;object-fit:cover;border-radius:10px;display:block;" alt=""></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
HTML);
    }
}
