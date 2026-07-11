<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class TeamBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('team')
            ->label('laravix::blocks.gjs.team')
            ->icon('fa-people-group')
            ->category('laravix::blocks.categories.social_proof')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--lg">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Our team</h2>
            <p class="lx-section-head__subtitle">The people behind the project.</p>
        </div>
        <div class="lx-team__grid">
            <div class="lx-team__member">
                <img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" class="lx-team__photo" alt="">
                <p class="lx-team__name">John Smith</p>
                <p class="lx-team__role">CEO &amp; Founder</p>
            </div>
            <div class="lx-team__member">
                <img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" class="lx-team__photo" alt="">
                <p class="lx-team__name">Jane Doe</p>
                <p class="lx-team__role">CTO</p>
            </div>
            <div class="lx-team__member">
                <img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" class="lx-team__photo" alt="">
                <p class="lx-team__name">Mike Johnson</p>
                <p class="lx-team__role">Lead Developer</p>
            </div>
            <div class="lx-team__member">
                <img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" class="lx-team__photo" alt="">
                <p class="lx-team__name">Sarah Brown</p>
                <p class="lx-team__role">Head of Design</p>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
