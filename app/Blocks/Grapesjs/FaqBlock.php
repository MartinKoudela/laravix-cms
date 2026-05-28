<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class FaqBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('faq')
            ->label('blocks.gjs.faq')
            ->icon('fa-circle-question')
            ->category('blocks.categories.info')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:720px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">Frequently asked questions</h2>
        <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">Answers to the most common customer questions.</p>
        <div style="display:flex;flex-direction:column;">
            <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">How can I get started?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Just register and start immediately. No credit card required.</p></div>
            <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Is there a free version?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Yes, we offer a free plan with no time limit.</p></div>
            <div style="border-bottom:1px solid #e5e7eb;padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">How does customer support work?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Email support is available on business days 9am–5pm.</p></div>
            <div style="padding:20px 0;"><h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Can I cancel my subscription at any time?</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.7;">Yes, without contractual obligations or hidden fees.</p></div>
        </div>
    </div>
</section>
HTML);
    }
}
