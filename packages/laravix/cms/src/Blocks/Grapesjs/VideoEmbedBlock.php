<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class VideoEmbedBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('video-embed')
            ->label('laravix::blocks.gjs.video_mp4')
            ->icon('fa-clapperboard')
            ->category('laravix::blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-video__inner">
        <video data-gjs-type="mp4-video" controls preload="metadata" class="lx-video__el"></video>
    </div>
</section>
HTML);
    }
}
