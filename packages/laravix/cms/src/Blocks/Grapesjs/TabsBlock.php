<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class TabsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('tabs')
            ->label('laravix::blocks.gjs.tabs')
            ->icon('fa-folder-open')
            ->category('laravix::blocks.categories.interactive')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container">
        <div class="lx-tabs" data-lx-tabs>
            <div class="lx-tabs__nav">
                <button class="lx-tabs__btn lx-tabs__btn--active" data-tab="lx-tab-1">Features</button>
                <button class="lx-tabs__btn" data-tab="lx-tab-2">Pricing</button>
                <button class="lx-tabs__btn" data-tab="lx-tab-3">FAQ</button>
            </div>
            <div class="lx-tabs__panel lx-tabs__panel--active" id="lx-tab-1">
                <h3 class="lx-tabs__panel-title">Everything you need to succeed</h3>
                <p class="lx-tabs__panel-text">Our platform comes with all the tools you need — from analytics and automation to collaboration and support. No plugins, no add-ons. Just one powerful product.</p>
            </div>
            <div class="lx-tabs__panel" id="lx-tab-2">
                <h3 class="lx-tabs__panel-title">Simple, transparent pricing</h3>
                <p class="lx-tabs__panel-text">Start free, upgrade when you're ready. No hidden fees, no long-term contracts. Cancel anytime with a single click.</p>
            </div>
            <div class="lx-tabs__panel" id="lx-tab-3">
                <h3 class="lx-tabs__panel-title">Common questions answered</h3>
                <p class="lx-tabs__panel-text">We've compiled answers to the most frequent questions our customers ask. If you can't find what you're looking for, our support team is always available.</p>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
