<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class CardsSliderBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('cards-slider')
            ->label('blocks.gjs.cards_slider')
            ->icon('fa-table-cells-large')
            ->category('blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-container">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Cards</h2>
        </div>
        <div class="swiper lx-cards-slider__swiper" data-loop="true" data-gap="24" data-breakpoints='{"0":{"slidesPerView":1},"640":{"slidesPerView":2},"1024":{"slidesPerView":3}}'>
            <div class="swiper-wrapper">
                <div class="swiper-slide"><div class="lx-cards-slider__card"><div class="lx-cards-slider__icon-wrap" style="background:#eff6ff;"><i class="fa-solid fa-bolt" style="color:#2563eb;font-size:1.25rem;"></i></div><h3 class="lx-cards-slider__card-title">Card 1</h3><p class="lx-cards-slider__card-text">Card description.</p></div></div>
                <div class="swiper-slide"><div class="lx-cards-slider__card"><div class="lx-cards-slider__icon-wrap" style="background:#f0fdf4;"><i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.25rem;"></i></div><h3 class="lx-cards-slider__card-title">Card 2</h3><p class="lx-cards-slider__card-text">Card description.</p></div></div>
                <div class="swiper-slide"><div class="lx-cards-slider__card"><div class="lx-cards-slider__icon-wrap" style="background:#fdf4ff;"><i class="fa-solid fa-star" style="color:#9333ea;font-size:1.25rem;"></i></div><h3 class="lx-cards-slider__card-title">Card 3</h3><p class="lx-cards-slider__card-text">Card description.</p></div></div>
                <div class="swiper-slide"><div class="lx-cards-slider__card"><div class="lx-cards-slider__icon-wrap" style="background:#fff7ed;"><i class="fa-solid fa-rocket" style="color:#ea580c;font-size:1.25rem;"></i></div><h3 class="lx-cards-slider__card-title">Card 4</h3><p class="lx-cards-slider__card-text">Card description.</p></div></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
HTML);
    }
}
