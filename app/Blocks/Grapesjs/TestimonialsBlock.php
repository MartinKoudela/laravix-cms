<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class TestimonialsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('testimonials')
            ->label('blocks.gjs.testimonials')
            ->icon('fa-comments')
            ->category('blocks.categories.social_proof')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-container">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">What customers say</h2>
            <p class="lx-section-head__subtitle">Read experiences from people who trust us.</p>
        </div>
        <div class="lx-testimonials__grid">
            <div class="lx-testimonials__card">
                <div class="lx-testimonials__stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                <p class="lx-testimonials__quote">"Great product, I recommend it to everyone."</p>
                <div class="lx-testimonials__author">
                    <div class="lx-testimonials__avatar"><i class="fa-solid fa-user"></i></div>
                    <div><p class="lx-testimonials__name">John Smith</p><p class="lx-testimonials__meta">CEO, Company Ltd.</p></div>
                </div>
            </div>
            <div class="lx-testimonials__card">
                <div class="lx-testimonials__stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                <p class="lx-testimonials__quote">"Amazing support and easy to use."</p>
                <div class="lx-testimonials__author">
                    <div class="lx-testimonials__avatar"><i class="fa-solid fa-user"></i></div>
                    <div><p class="lx-testimonials__name">Jane Doe</p><p class="lx-testimonials__meta">Freelance designer</p></div>
                </div>
            </div>
            <div class="lx-testimonials__card">
                <div class="lx-testimonials__stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                <p class="lx-testimonials__quote">"Best investment of this year."</p>
                <div class="lx-testimonials__author">
                    <div class="lx-testimonials__avatar"><i class="fa-solid fa-user"></i></div>
                    <div><p class="lx-testimonials__name">Mike Johnson</p><p class="lx-testimonials__meta">Entrepreneur</p></div>
                </div>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
