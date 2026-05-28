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
<section style="position:relative;min-height:500px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#000;">
    <video data-gjs-type="mp4-video" autoplay muted loop playsinline preload="metadata" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;opacity:0.5;"></video>
    <div style="position:relative;z-index:1;text-align:center;padding:40px 24px;max-width:720px;">
        <h1 style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 16px;line-height:1.15;">Heading with video background</h1>
        <p style="font-size:1.25rem;color:rgba(255,255,255,.8);margin:0 0 36px;line-height:1.7;">Brief description or call to action.</p>
        <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;cursor:pointer;background:#fff;color:#111827;border:2px solid #fff;">Get started</a>
    </div>
</section>
HTML);
    }
}
