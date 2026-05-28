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
<section style="padding:64px 24px;background:#f9fafb;">
    <div style="max-width:1100px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">What customers say</h2>
        <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">Read experiences from people who trust us.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
            <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;font-style:italic;">"Great product, I recommend it to everyone."</p>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                    <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">John Smith</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">CEO, Company Ltd.</p></div>
                </div>
            </div>
            <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;font-style:italic;">"Amazing support and easy to use."</p>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                    <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">Jane Doe</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">Freelance designer</p></div>
                </div>
            </div>
            <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <div style="display:flex;gap:2px;margin:0 0 16px;"><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i><i class="fa-solid fa-star" style="color:#f59e0b;"></i></div>
                <p style="font-size:.9375rem;color:#374151;line-height:1.7;margin:0 0 20px;font-style:italic;">"Best investment of this year."</p>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" style="color:#9ca3af;"></i></div>
                    <div><p style="font-size:.875rem;font-weight:600;color:#111827;margin:0;">Mike Johnson</p><p style="font-size:.8125rem;color:#9ca3af;margin:0;">Entrepreneur</p></div>
                </div>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
