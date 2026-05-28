<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class AccordionBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('accordion')
            ->label('blocks.gjs.accordion')
            ->icon('fa-bars-staggered')
            ->category('blocks.categories.info')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:720px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Frequently asked questions</h2>
        <details style="border-bottom:1px solid #e5e7eb;">
            <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>How can I get started?</span><i class="fa-solid fa-chevron-down"></i></summary>
            <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">Just register and start immediately. No credit card required.</p>
        </details>
        <details style="border-bottom:1px solid #e5e7eb;">
            <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>Is there a free version?</span><i class="fa-solid fa-chevron-down"></i></summary>
            <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">Yes, we offer a free plan with no time limit.</p>
        </details>
        <details style="border-bottom:1px solid #e5e7eb;">
            <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>How does customer support work?</span><i class="fa-solid fa-chevron-down"></i></summary>
            <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">Email support is available on business days 9am–5pm.</p>
        </details>
        <details>
            <summary style="padding:20px 0;font-size:1rem;font-weight:600;color:#111827;cursor:pointer;display:flex;justify-content:space-between;align-items:center;list-style:none;-webkit-appearance:none;"><span>Can I cancel my subscription at any time?</span><i class="fa-solid fa-chevron-down"></i></summary>
            <p style="font-size:.9375rem;color:#6b7280;padding:0 0 20px;margin:0;line-height:1.7;">Yes, without contractual obligations or hidden fees.</p>
        </details>
    </div>
</section>
HTML);
    }
}
