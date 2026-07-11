<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class ProgressBarsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('progress-bars')
            ->label('blocks.gjs.progress_bars')
            ->icon('fa-bars-progress')
            ->category('blocks.categories.interactive')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--md">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Our expertise</h2>
            <p class="lx-section-head__subtitle">Skills and competencies we bring to every project.</p>
        </div>
        <div class="lx-progress">
            <div class="lx-progress__item">
                <div class="lx-progress__header"><span class="lx-progress__name">UI/UX Design</span><span class="lx-progress__value">95%</span></div>
                <div class="lx-progress__bar"><div class="lx-progress__fill" style="width:95%;"></div></div>
            </div>
            <div class="lx-progress__item">
                <div class="lx-progress__header"><span class="lx-progress__name">Frontend Development</span><span class="lx-progress__value">90%</span></div>
                <div class="lx-progress__bar"><div class="lx-progress__fill" style="width:90%;background:#16a34a;"></div></div>
            </div>
            <div class="lx-progress__item">
                <div class="lx-progress__header"><span class="lx-progress__name">Backend &amp; APIs</span><span class="lx-progress__value">85%</span></div>
                <div class="lx-progress__bar"><div class="lx-progress__fill" style="width:85%;background:#9333ea;"></div></div>
            </div>
            <div class="lx-progress__item">
                <div class="lx-progress__header"><span class="lx-progress__name">Product Strategy</span><span class="lx-progress__value">80%</span></div>
                <div class="lx-progress__bar"><div class="lx-progress__fill" style="width:80%;background:#ea580c;"></div></div>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
