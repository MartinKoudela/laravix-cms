<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class GalleryBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('gallery')
            ->label('blocks.gjs.gallery')
            ->icon('fa-images')
            ->category('blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:1100px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">Gallery</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
            <img src="https://placehold.co/600x400?text=1" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
            <img src="https://placehold.co/600x400?text=2" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
            <img src="https://placehold.co/600x400?text=3" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
            <img src="https://placehold.co/600x400?text=4" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
            <img src="https://placehold.co/600x400?text=5" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
            <img src="https://placehold.co/600x400?text=6" data-gjs-type="media-image" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;display:block;" alt="">
        </div>
    </div>
</section>
HTML);
    }
}
