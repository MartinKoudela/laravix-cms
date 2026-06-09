<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class TestimonialsSliderBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('testimonials-slider')
            ->label('blocks.gjs.testimonials_slider')
            ->icon('fa-quote-left')
            ->category('blocks.categories.social_proof')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-container--md">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">What customers say</h2>
        </div>
        <div class="swiper lx-testimonials-slider__swiper" data-loop="true" data-autoplay="4000">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="lx-testimonials-slider__card">
                        <div class="lx-testimonials-slider__stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <p class="lx-testimonials-slider__quote">"Great product, I recommend it to everyone."</p>
                        <div class="lx-testimonials-slider__author">
                            <div class="lx-testimonials-slider__avatar"><i class="fa-solid fa-user"></i></div>
                            <div style="text-align:left;"><p class="lx-testimonials-slider__name">John Smith</p><p class="lx-testimonials-slider__meta">CEO, Company Ltd.</p></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="lx-testimonials-slider__card">
                        <div class="lx-testimonials-slider__stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <p class="lx-testimonials-slider__quote">"Amazing support and easy to use."</p>
                        <div class="lx-testimonials-slider__author">
                            <div class="lx-testimonials-slider__avatar"><i class="fa-solid fa-user"></i></div>
                            <div style="text-align:left;"><p class="lx-testimonials-slider__name">Jane Doe</p><p class="lx-testimonials-slider__meta">Freelance designer</p></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
HTML);
    }
}
