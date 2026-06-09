<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class VideoHeroBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('video-hero')
            ->label('blocks.gjs.video_hero')
            ->icon('fa-circle-play')
            ->category('blocks.categories.hero')
            ->canvasHtml(<<<'HTML'
<section class="lx-hero-video">
    <video data-gjs-type="mp4-video" autoplay muted loop playsinline preload="metadata" class="lx-hero-video__bg"></video>
    <div class="lx-hero-video__content">
        <h1 class="lx-hero-video__title">Heading with video background</h1>
        <p class="lx-hero-video__text">Brief description or call to action.</p>
        <a href="#" data-gjs-type="button-link" class="lx-btn lx-btn--white lx-btn--lg">Get started</a>
    </div>
</section>
HTML);
    }
}
