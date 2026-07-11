<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class AnimatedCounterBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('animated-counter')
            ->label('laravix::blocks.gjs.animated_counter')
            ->icon('fa-arrow-up-right-dots')
            ->category('laravix::blocks.categories.interactive')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-counter__grid lx-container">
        <div class="lx-counter__item">
            <span class="lx-counter__num" data-lx-counter data-target="10000" data-suffix="+">0</span>
            <p class="lx-counter__label">Happy customers</p>
        </div>
        <div class="lx-counter__item">
            <span class="lx-counter__num" data-lx-counter data-target="99" data-suffix=".9%">0</span>
            <p class="lx-counter__label">Service uptime</p>
        </div>
        <div class="lx-counter__item">
            <span class="lx-counter__num" data-lx-counter data-target="50" data-suffix="+">0</span>
            <p class="lx-counter__label">Countries</p>
        </div>
        <div class="lx-counter__item">
            <span class="lx-counter__num" data-lx-counter data-target="500" data-suffix="+">0</span>
            <p class="lx-counter__label">Team members</p>
        </div>
    </div>
</section>
HTML);
    }
}
