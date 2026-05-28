<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class VideoEmbedBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('video-embed')
            ->label('blocks.gjs.video_mp4')
            ->icon('fa-clapperboard')
            ->category('blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:800px;margin:0 auto;">
        <video data-gjs-type="mp4-video" controls preload="metadata" style="width:100%;border-radius:12px;display:block;"></video>
    </div>
</section>
HTML);
    }
}
