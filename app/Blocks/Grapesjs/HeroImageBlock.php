<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class HeroImageBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('hero-image')
            ->label('blocks.gjs.hero_image')
            ->icon('fa-image')
            ->category('blocks.categories.hero')
            ->canvasHtml(<<<'HTML'
<section style="padding:80px 24px;background:#f9fafb;">
    <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;gap:64px;flex-wrap:wrap;">
        <div style="flex:1;min-width:280px;">
            <h1 style="font-size:2.75rem;font-weight:800;color:#111827;margin:0 0 16px;line-height:1.2;">Heading with a great image</h1>
            <p style="font-size:1.125rem;color:#6b7280;margin:0 0 32px;line-height:1.7;">Product or service description.</p>
            <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#111827;color:#fff;border:2px solid #111827;">Start for free</a>
        </div>
        <div style="flex:1;min-width:280px;">
            <img src="https://placehold.co/600x400?text=Foto" data-gjs-type="media-image" style="width:100%;border-radius:16px;display:block;" alt="">
        </div>
    </div>
</section>
HTML);
    }
}
