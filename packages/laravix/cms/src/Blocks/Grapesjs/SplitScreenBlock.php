<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class SplitScreenBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('split-screen')
            ->label('blocks.gjs.split_screen')
            ->icon('fa-table-columns')
            ->category('blocks.categories.artistic')
            ->canvasHtml(<<<'HTML'
<div class="lx-split">
    <div class="lx-split__half lx-split__half--dark">
        <div class="lx-split__content">
            <p class="lx-split__label">Our vision</p>
            <h2 class="lx-split__title">We build things that matter.</h2>
            <p class="lx-split__text">Bold ideas require bold execution. We're here to make it happen.</p>
            <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--white">Learn more</a>
        </div>
    </div>
    <div class="lx-split__half lx-split__half--light">
        <div class="lx-split__content">
            <p class="lx-split__label">Our work</p>
            <h2 class="lx-split__title">Crafted with precision.</h2>
            <p class="lx-split__text">Every detail is intentional. Every decision is purposeful.</p>
            <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--dark">See portfolio</a>
        </div>
    </div>
</div>
HTML);
    }
}
