<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class StickyCtaBarBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('sticky-cta-bar')
            ->label('blocks.gjs.sticky_cta_bar')
            ->icon('fa-thumbtack')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<div class="lx-sticky-cta">
    <div class="lx-sticky-cta__inner">
        <p class="lx-sticky-cta__text"><strong>Limited time offer</strong> — Save 30% on all plans this week.</p>
        <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--white">Claim offer</a>
    </div>
</div>
HTML);
    }
}
