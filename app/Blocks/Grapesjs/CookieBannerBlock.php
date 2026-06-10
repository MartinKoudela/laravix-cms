<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class CookieBannerBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('cookie-banner')
            ->label('blocks.gjs.cookie_banner')
            ->icon('fa-cookie-bite')
            ->category('blocks.categories.elements')
            ->canvasHtml(<<<'HTML'
<div class="lx-cookie" id="lx-cookie-banner">
    <p class="lx-cookie__text">We use cookies to improve your experience and analyze traffic. <a href="#">Learn more</a></p>
    <div class="lx-cookie__actions">
        <button class="lx-cookie__btn lx-cookie__btn--accept">Accept all</button>
        <button class="lx-cookie__btn lx-cookie__btn--decline">Decline</button>
    </div>
</div>
HTML);
    }
}
