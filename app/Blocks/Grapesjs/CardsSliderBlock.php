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
<section style="padding:64px 24px;background:#f9fafb;">
    <div style="max-width:1100px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Cards</h2>
        <div class="swiper" data-loop="true" data-gap="24" data-breakpoints='{"0":{"slidesPerView":1},"640":{"slidesPerView":2},"1024":{"slidesPerView":3}}' style="overflow:hidden;padding-bottom:44px;">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);"><div style="width:48px;height:48px;background:#eff6ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-bolt" style="color:#2563eb;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Card 1</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Card description.</p></div></div>
                <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);"><div style="width:48px;height:48px;background:#f0fdf4;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Card 2</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Card description.</p></div></div>
                <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);"><div style="width:48px;height:48px;background:#fdf4ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-star" style="color:#9333ea;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Card 3</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Card description.</p></div></div>
                <div class="swiper-slide"><div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);"><div style="width:48px;height:48px;background:#fff7ed;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-rocket" style="color:#ea580c;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Card 4</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Card description.</p></div></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
HTML);
    }
}
