<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class LogoBarBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('logo-bar')
            ->label('blocks.gjs.logo_bar')
            ->icon('fa-building')
            ->category('blocks.categories.social_proof')
            ->canvasHtml(<<<'HTML'
<div class="lx-logo-bar">
    <div class="lx-logo-bar__inner">
        <p class="lx-logo-bar__label">Trusted by</p>
        <div class="lx-logo-bar__list">
            <span class="lx-logo-bar__item">Company A</span>
            <span class="lx-logo-bar__item">Company B</span>
            <span class="lx-logo-bar__item">Company C</span>
            <span class="lx-logo-bar__item">Company D</span>
            <span class="lx-logo-bar__item">Company E</span>
        </div>
    </div>
</div>
HTML);
    }
}
