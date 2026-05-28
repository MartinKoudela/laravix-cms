<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class MapBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('map')
            ->label('blocks.gjs.map')
            ->icon('fa-map-location-dot')
            ->category('blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div data-gjs-type="map-embed" style="max-width:1100px;margin:0 auto;border-radius:12px;overflow:hidden;height:420px;">
        <iframe src="https://maps.google.com/maps?q=Praha&output=embed&z=13" width="100%" height="100%" style="border:none;display:block;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>
HTML);
    }
}
