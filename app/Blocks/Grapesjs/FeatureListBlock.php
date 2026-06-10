<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class FeatureListBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('feature-list')
            ->label('blocks.gjs.feature_list')
            ->icon('fa-list-check')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Everything you need</h2>
            <p class="lx-section-head__subtitle">Powerful features designed to help you grow faster.</p>
        </div>
        <div class="lx-features__grid">
            <div class="lx-features__item">
                <div class="lx-features__icon"><i class="fa-solid fa-bolt"></i></div>
                <div><h3 class="lx-features__title">Fast setup</h3><p class="lx-features__text">Get up and running in minutes, not days.</p></div>
            </div>
            <div class="lx-features__item">
                <div class="lx-features__icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-shield-halved"></i></div>
                <div><h3 class="lx-features__title">Secure by default</h3><p class="lx-features__text">Enterprise-grade security out of the box.</p></div>
            </div>
            <div class="lx-features__item">
                <div class="lx-features__icon" style="background:#fdf4ff;color:#9333ea;"><i class="fa-solid fa-chart-line"></i></div>
                <div><h3 class="lx-features__title">Analytics built in</h3><p class="lx-features__text">Understand your users with real-time data.</p></div>
            </div>
            <div class="lx-features__item">
                <div class="lx-features__icon" style="background:#fff7ed;color:#ea580c;"><i class="fa-solid fa-puzzle-piece"></i></div>
                <div><h3 class="lx-features__title">500+ integrations</h3><p class="lx-features__text">Connect with the tools you already use.</p></div>
            </div>
            <div class="lx-features__item">
                <div class="lx-features__icon" style="background:#fefce8;color:#ca8a04;"><i class="fa-solid fa-headset"></i></div>
                <div><h3 class="lx-features__title">24/7 support</h3><p class="lx-features__text">Our team is always here when you need us.</p></div>
            </div>
            <div class="lx-features__item">
                <div class="lx-features__icon" style="background:#fff1f2;color:#e11d48;"><i class="fa-solid fa-mobile-screen"></i></div>
                <div><h3 class="lx-features__title">Mobile ready</h3><p class="lx-features__text">Works beautifully on any device or screen.</p></div>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
