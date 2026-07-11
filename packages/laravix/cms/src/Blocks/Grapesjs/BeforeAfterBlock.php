<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class BeforeAfterBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('before-after')
            ->label('laravix::blocks.gjs.before_after')
            ->icon('fa-sliders')
            ->category('laravix::blocks.categories.interactive')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--md">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">See the difference</h2>
            <p class="lx-section-head__subtitle">Drag the slider to compare before and after.</p>
        </div>
        <div class="lx-before-after" data-lx-before-after>
            <img src="https://placehold.co/800x400/e5e7eb/6b7280?text=Before" alt="Before">
            <div class="lx-before-after__after">
                <img src="https://placehold.co/800x400/111827/ffffff?text=After" alt="After">
            </div>
            <div class="lx-before-after__handle"></div>
            <span class="lx-before-after__label lx-before-after__label--before">Before</span>
            <span class="lx-before-after__label lx-before-after__label--after">After</span>
            <input type="range" class="lx-before-after__range" min="0" max="100" value="50">
        </div>
    </div>
</section>
HTML);
    }
}
