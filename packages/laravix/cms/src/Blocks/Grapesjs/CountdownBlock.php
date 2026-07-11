<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class CountdownBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('countdown')
            ->label('laravix::blocks.gjs.countdown')
            ->icon('fa-hourglass-half')
            ->category('laravix::blocks.categories.interactive')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--dark" style="text-align:center;">
    <div class="lx-container--sm">
        <p class="lx-section-head__subtitle lx-section-head__subtitle--white" style="margin-bottom:8px;">Limited time offer</p>
        <h2 class="lx-section-head__title lx-section-head__title--white" style="margin-bottom:40px;">Deal ends in</h2>
        <div class="lx-countdown" data-lx-countdown data-target="2026-12-31T23:59:59">
            <div class="lx-countdown__grid">
                <div class="lx-countdown__unit"><span class="lx-countdown__num" data-days>00</span><span class="lx-countdown__label">Days</span></div>
                <div class="lx-countdown__sep">:</div>
                <div class="lx-countdown__unit"><span class="lx-countdown__num" data-hours>00</span><span class="lx-countdown__label">Hours</span></div>
                <div class="lx-countdown__sep">:</div>
                <div class="lx-countdown__unit"><span class="lx-countdown__num" data-minutes>00</span><span class="lx-countdown__label">Minutes</span></div>
                <div class="lx-countdown__sep">:</div>
                <div class="lx-countdown__unit"><span class="lx-countdown__num" data-seconds>00</span><span class="lx-countdown__label">Seconds</span></div>
            </div>
        </div>
        <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--white lx-btn--lg" style="margin-top:40px;">Claim your deal</a>
    </div>
</section>
HTML);
    }
}
