<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class YoutubeBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('video-youtube')
            ->label('blocks.gjs.youtube')
            ->icon('fa-brands fa-youtube')
            ->category('blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-youtube__inner">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Video</h2>
        </div>
        <div data-gjs-type="youtube-video" class="lx-youtube__embed">
            <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0" frameborder="0" allowfullscreen allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" class="lx-youtube__iframe"></iframe>
        </div>
    </div>
</section>
HTML);
    }
}
