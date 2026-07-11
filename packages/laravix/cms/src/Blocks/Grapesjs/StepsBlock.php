<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class StepsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('steps')
            ->label('blocks.gjs.steps')
            ->icon('fa-list-ol')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--lg">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">How it works</h2>
            <p class="lx-section-head__subtitle">Three simple steps to the result.</p>
        </div>
        <div class="lx-steps__grid">
            <div class="lx-steps__item">
                <div class="lx-steps__number">1</div>
                <h3 class="lx-steps__title">Registration</h3>
                <p class="lx-steps__text">Create your account in 30 seconds.</p>
            </div>
            <div class="lx-steps__item">
                <div class="lx-steps__number">2</div>
                <h3 class="lx-steps__title">Setup</h3>
                <p class="lx-steps__text">Customize your environment.</p>
            </div>
            <div class="lx-steps__item">
                <div class="lx-steps__number">3</div>
                <h3 class="lx-steps__title">Launch</h3>
                <p class="lx-steps__text">Publish and track results.</p>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
