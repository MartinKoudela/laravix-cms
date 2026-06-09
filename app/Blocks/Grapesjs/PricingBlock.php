<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class PricingBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('pricing')
            ->label('blocks.gjs.pricing')
            ->icon('fa-tag')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-container--lg">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Simple pricing</h2>
            <p class="lx-section-head__subtitle">Choose the plan that suits you. No hidden fees.</p>
        </div>
        <div class="lx-pricing__grid">
            <div class="lx-pricing__card">
                <h3 class="lx-pricing__tier">Starter</h3>
                <p class="lx-pricing__price">$0</p>
                <p class="lx-pricing__period">/ month</p>
                <ul class="lx-pricing__features">
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#16a34a;"></i> 1 website</li>
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#16a34a;"></i> 5 pages</li>
                    <li class="lx-pricing__feature lx-pricing__feature--disabled"><i class="fa-solid fa-xmark" style="color:#d1d5db;"></i> Custom domain</li>
                </ul>
                <a href="#" data-gjs-type="button-link" class="lx-pricing__cta">Start for free</a>
            </div>
            <div class="lx-pricing__card lx-pricing__card--featured">
                <h3 class="lx-pricing__tier">Pro</h3>
                <p class="lx-pricing__price">$19</p>
                <p class="lx-pricing__period">/ month</p>
                <ul class="lx-pricing__features">
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#4ade80;"></i> 5 websites</li>
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#4ade80;"></i> Unlimited pages</li>
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#4ade80;"></i> Custom domain</li>
                </ul>
                <a href="#" data-gjs-type="button-link" class="lx-pricing__cta">Choose Pro</a>
            </div>
            <div class="lx-pricing__card">
                <h3 class="lx-pricing__tier">Enterprise</h3>
                <p class="lx-pricing__price">Custom</p>
                <p class="lx-pricing__period">by agreement</p>
                <ul class="lx-pricing__features">
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#16a34a;"></i> Unlimited websites</li>
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#16a34a;"></i> SLA support</li>
                    <li class="lx-pricing__feature"><i class="fa-solid fa-check" style="color:#16a34a;"></i> Custom integrations</li>
                </ul>
                <a href="#" data-gjs-type="button-link" class="lx-pricing__cta">Contact us</a>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
