<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class QuoteBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('quote')
            ->label('blocks.gjs.quote')
            ->icon('fa-quote-right')
            ->category('blocks.categories.artistic')
            ->canvasHtml(<<<'HTML'
<div class="lx-quote">
    <i class="fa-solid fa-quote-left lx-quote__mark"></i>
    <blockquote class="lx-quote__text">The best way to predict the future is to create it.</blockquote>
    <cite class="lx-quote__author"><strong>Peter Drucker</strong> — Management consultant</cite>
</div>
HTML);
    }
}
