<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class CtaBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('cta')
            ->label('laravix::blocks.gjs.cta')
            ->icon('fa-bullhorn')
            ->category('laravix::blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--dark" style="text-align:center;">
    <div class="lx-container--sm">
        <h2 class="lx-cta__title">Ready to get started?</h2>
        <p class="lx-cta__text">Join thousands of satisfied customers today.</p>
        <div class="lx-cta__actions">
            <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--white lx-btn--lg">Start for free</a>
            <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--outline lx-btn--lg">Contact us</a>
        </div>
    </div>
</section>
HTML);
    }
}
