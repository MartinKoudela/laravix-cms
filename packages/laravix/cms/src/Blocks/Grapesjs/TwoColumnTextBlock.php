<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class TwoColumnTextBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('two-column-text')
            ->label('laravix::blocks.gjs.two_column_text')
            ->icon('fa-columns')
            ->category('laravix::blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container">
        <div class="lx-two-col">
            <div class="lx-two-col__left">
                <h2 class="lx-two-col__title">About our approach</h2>
            </div>
            <div class="lx-two-col__right lx-two-col__body">
                <p>We believe great products come from deep understanding of the people who use them. That's why we spend more time listening than building.</p>
                <p>Our process combines rigorous research with rapid iteration — moving fast enough to stay relevant, carefully enough to get it right.</p>
                <p>The result is software that feels natural to use, because it was designed around real human needs from the very beginning.</p>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
