<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class BigTypographyBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('big-typography')
            ->label('blocks.gjs.big_typography')
            ->icon('fa-font')
            ->category('blocks.categories.artistic')
            ->canvasHtml(<<<'HTML'
<section class="lx-bigtype">
    <p class="lx-bigtype__label">Our mission</p>
    <h2 class="lx-bigtype__text">We build<br>the future.</h2>
    <p class="lx-bigtype__sub">One product at a time — with intention, craft, and care.</p>
</section>
HTML);
    }
}
