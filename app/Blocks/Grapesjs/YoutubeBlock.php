<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class YoutubeBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('video-youtube')
            ->label('blocks.gjs.youtube')
            ->icon('fa-brands fa-youtube')
            ->category('blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;background:#f9fafb;">
    <div style="max-width:800px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 32px;">Video</h2>
        <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:12px;">
            <iframe data-gjs-type="youtube-video" src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0" frameborder="0" allowfullscreen allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture" style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe>
        </div>
    </div>
</section>
HTML);
    }
}
