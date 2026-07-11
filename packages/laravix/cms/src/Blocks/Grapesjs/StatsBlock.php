<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class StatsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('stats')
            ->label('blocks.gjs.stats')
            ->icon('fa-chart-bar')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--dark">
    <div class="lx-stats__grid lx-container--lg">
        <div><p class="lx-stats__value">10,000+</p><p class="lx-stats__label">Happy customers</p></div>
        <div><p class="lx-stats__value">99.9%</p><p class="lx-stats__label">Service uptime</p></div>
        <div><p class="lx-stats__value">50+</p><p class="lx-stats__label">Countries worldwide</p></div>
        <div><p class="lx-stats__value">24/7</p><p class="lx-stats__label">Customer support</p></div>
    </div>
</section>
HTML);
    }
}
