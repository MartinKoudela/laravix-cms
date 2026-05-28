<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class CtaBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('cta')
            ->label('blocks.gjs.cta')
            ->icon('fa-bullhorn')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section style="padding:80px 24px;background:#111827;text-align:center;">
    <div style="max-width:600px;margin:0 auto;">
        <h2 style="font-size:2.25rem;font-weight:800;color:#fff;margin:0 0 16px;">Ready to get started?</h2>
        <p style="font-size:1.125rem;color:#9ca3af;margin:0 0 32px;line-height:1.7;">Join thousands of satisfied customers today.</p>
        <div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
            <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#fff;color:#111827;border:2px solid #fff;">Start for free</a>
            <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:transparent;color:#fff;border:2px solid #4b5563;">Contact us</a>
        </div>
    </div>
</section>
HTML);
    }
}
