<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class MapBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('map')
            ->label('laravix::blocks.gjs.map')
            ->icon('fa-map-location-dot')
            ->category('laravix::blocks.categories.media')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div data-gjs-type="map-embed" class="lx-map__embed">
        <iframe src="https://maps.google.com/maps?q=Praha&output=embed&z=13" class="lx-map__iframe" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>
HTML);
    }
}
