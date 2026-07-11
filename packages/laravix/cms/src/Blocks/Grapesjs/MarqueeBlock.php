<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class MarqueeBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('marquee')
            ->label('blocks.gjs.marquee')
            ->icon('fa-repeat')
            ->category('blocks.categories.artistic')
            ->canvasHtml(<<<'HTML'
<div class="lx-marquee">
    <div class="lx-marquee__track">
        <span class="lx-marquee__item">Design</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Development</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Strategy</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Innovation</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Growth</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Design</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Development</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Strategy</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Innovation</span>
        <span class="lx-marquee__sep">✦</span>
        <span class="lx-marquee__item">Growth</span>
        <span class="lx-marquee__sep">✦</span>
    </div>
</div>
HTML);
    }
}
