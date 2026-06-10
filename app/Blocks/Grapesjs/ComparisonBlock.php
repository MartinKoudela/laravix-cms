<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class ComparisonBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('comparison')
            ->label('blocks.gjs.comparison')
            ->icon('fa-scale-balanced')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--lg">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Why choose us?</h2>
            <p class="lx-section-head__subtitle">See how we compare to the competition.</p>
        </div>
        <div class="lx-comparison">
            <div class="lx-comparison__header">
                <div class="lx-comparison__feature"></div>
                <div class="lx-comparison__col-head lx-comparison__col-head--us">Us ✦</div>
                <div class="lx-comparison__col-head">Others</div>
            </div>
            <div class="lx-comparison__row">
                <span class="lx-comparison__feature">Unlimited projects</span>
                <span class="lx-comparison__check lx-comparison__check--yes"><i class="fa-solid fa-check"></i></span>
                <span class="lx-comparison__check lx-comparison__check--no"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="lx-comparison__row">
                <span class="lx-comparison__feature">24/7 support</span>
                <span class="lx-comparison__check lx-comparison__check--yes"><i class="fa-solid fa-check"></i></span>
                <span class="lx-comparison__check lx-comparison__check--no"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="lx-comparison__row">
                <span class="lx-comparison__feature">No setup fee</span>
                <span class="lx-comparison__check lx-comparison__check--yes"><i class="fa-solid fa-check"></i></span>
                <span class="lx-comparison__check lx-comparison__check--no"><i class="fa-solid fa-xmark"></i></span>
            </div>
            <div class="lx-comparison__row">
                <span class="lx-comparison__feature">Cancel anytime</span>
                <span class="lx-comparison__check lx-comparison__check--yes"><i class="fa-solid fa-check"></i></span>
                <span class="lx-comparison__check lx-comparison__check--yes"><i class="fa-solid fa-check"></i></span>
            </div>
            <div class="lx-comparison__row">
                <span class="lx-comparison__feature">API access</span>
                <span class="lx-comparison__check lx-comparison__check--yes"><i class="fa-solid fa-check"></i></span>
                <span class="lx-comparison__check lx-comparison__check--no"><i class="fa-solid fa-xmark"></i></span>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
