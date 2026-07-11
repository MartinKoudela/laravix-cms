<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class BentoGridBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('bento-grid')
            ->label('laravix::blocks.gjs.bento_grid')
            ->icon('fa-border-all')
            ->category('laravix::blocks.categories.artistic')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-bento">
        <div class="lx-bento__card lx-bento__card--wide lx-bento__card--dark">
            <i class="fa-solid fa-bolt lx-bento__icon"></i>
            <h3 class="lx-bento__title">Lightning fast performance</h3>
            <p class="lx-bento__text">Built for speed from the ground up.</p>
        </div>
        <div class="lx-bento__card lx-bento__card--tall lx-bento__card--blue">
            <i class="fa-solid fa-shield-halved lx-bento__icon" style="color:#bfdbfe;"></i>
            <h3 class="lx-bento__title">Enterprise security</h3>
            <p class="lx-bento__text">Your data is safe with bank-level encryption.</p>
        </div>
        <div class="lx-bento__card lx-bento__card--green">
            <span class="lx-bento__label">Uptime</span>
            <h3 class="lx-bento__title">99.9%</h3>
        </div>
        <div class="lx-bento__card">
            <span class="lx-bento__label">Customers</span>
            <h3 class="lx-bento__title">10,000+</h3>
        </div>
        <div class="lx-bento__card lx-bento__card--wide lx-bento__card--purple">
            <i class="fa-solid fa-wand-magic-sparkles lx-bento__icon" style="color:#d8b4fe;"></i>
            <h3 class="lx-bento__title">AI-powered tools that adapt to you</h3>
        </div>
    </div>
</section>
HTML);
    }
}
