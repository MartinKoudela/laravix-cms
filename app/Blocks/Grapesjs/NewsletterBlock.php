<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class NewsletterBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('newsletter')
            ->label('blocks.gjs.newsletter')
            ->icon('fa-at')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;background:#eff6ff;">
    <div style="max-width:560px;margin:0 auto;text-align:center;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 12px;">Stay informed</h2>
        <p style="font-size:1rem;color:#6b7280;margin:0 0 32px;line-height:1.7;">News and tips straight to your email. No spam, unsubscribe anytime.</p>
        <form data-contact-form style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
            <input name="email" type="email" placeholder="your@email.com" required style="flex:1;min-width:220px;padding:12px 16px;border:1px solid #bfdbfe;border-radius:8px;font-size:.9375rem;background:#fff;box-sizing:border-box;">
            <button type="submit" style="padding:12px 24px;background:#2563eb;color:#fff;font-weight:600;border:none;border-radius:8px;font-size:.9375rem;cursor:pointer;white-space:nowrap;">Subscribe</button>
        </form>
    </div>
</section>
HTML);
    }
}
