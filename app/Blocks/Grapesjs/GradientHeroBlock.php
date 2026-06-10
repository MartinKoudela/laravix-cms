<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class GradientHeroBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('gradient-hero')
            ->label('blocks.gjs.gradient_hero')
            ->icon('fa-wand-magic-sparkles')
            ->category('blocks.categories.artistic')
            ->canvasHtml(<<<'HTML'
<section class="lx-gradient-hero">
    <div class="lx-gradient-hero__content">
        <span class="lx-badge" style="background:rgba(255,255,255,.2);color:#fff;margin-bottom:20px;display:inline-block;">New release ✦</span>
        <h1 class="lx-gradient-hero__title">Something beautiful<br>is coming.</h1>
        <p class="lx-gradient-hero__text">Create stunning experiences without writing a single line of code.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--white lx-btn--lg">Get started free</a>
            <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--outline lx-btn--lg">Watch demo</a>
        </div>
    </div>
</section>
HTML);
    }
}
