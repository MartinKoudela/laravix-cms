<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class TimelineBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('timeline')
            ->label('blocks.gjs.timeline')
            ->icon('fa-timeline')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--md">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Our journey</h2>
            <p class="lx-section-head__subtitle">Key milestones that shaped who we are.</p>
        </div>
        <div class="lx-timeline">
            <div class="lx-timeline__item">
                <div class="lx-timeline__dot"></div>
                <span class="lx-timeline__year">2021</span>
                <h3 class="lx-timeline__title">Founded</h3>
                <p class="lx-timeline__text">Started with a small team and a big vision to change the industry.</p>
            </div>
            <div class="lx-timeline__item">
                <div class="lx-timeline__dot"></div>
                <span class="lx-timeline__year">2022</span>
                <h3 class="lx-timeline__title">First 1,000 customers</h3>
                <p class="lx-timeline__text">Reached our first major milestone faster than expected.</p>
            </div>
            <div class="lx-timeline__item">
                <div class="lx-timeline__dot"></div>
                <span class="lx-timeline__year">2023</span>
                <h3 class="lx-timeline__title">Series A funding</h3>
                <p class="lx-timeline__text">Raised $5M to accelerate product development and team growth.</p>
            </div>
            <div class="lx-timeline__item">
                <div class="lx-timeline__dot"></div>
                <span class="lx-timeline__year">2024</span>
                <h3 class="lx-timeline__title">Global expansion</h3>
                <p class="lx-timeline__text">Now serving customers in 50+ countries worldwide.</p>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
