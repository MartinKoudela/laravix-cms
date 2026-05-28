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
<section style="padding:64px 24px;background:#f9fafb;">
    <div style="max-width:760px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">What customers say</h2>
        <div class="swiper" data-loop="true" data-autoplay="4000" style="overflow:hidden;padding-bottom:44px;">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><div style="background:#fff;border-radius:16px;padding:36px;box-shadow:0 1px 4px rgba(0,0,0,.06);text-align:center;"><div style="display:flex;gap:2px;justify-content:center;margin:0 0 20px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div><p style="font-size:1.125rem;color:#374151;line-height:1.7;margin:0 0 28px;font-style:italic;">"Great product, I recommend it to everyone."</p><div style="display:flex;align-items:center;justify-content:center;gap:12px;"><div style="width:44px;height:44px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div><div style="text-align:left;"><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">John Smith</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">CEO, Company Ltd.</p></div></div></div></div>
                <div class="swiper-slide"><div style="background:#fff;border-radius:16px;padding:36px;box-shadow:0 1px 4px rgba(0,0,0,.06);text-align:center;"><div style="display:flex;gap:2px;justify-content:center;margin:0 0 20px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div><p style="font-size:1.125rem;color:#374151;line-height:1.7;margin:0 0 28px;font-style:italic;">"Amazing support and easy to use."</p><div style="display:flex;align-items:center;justify-content:center;gap:12px;"><div style="width:44px;height:44px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div><div style="text-align:left;"><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">Jane Doe</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">Freelance designer</p></div></div></div></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
HTML);
    }
}
