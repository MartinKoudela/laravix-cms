<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class FaqBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('faq')
            ->label('laravix::blocks.gjs.faq')
            ->icon('fa-circle-question')
            ->category('laravix::blocks.categories.info')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--md">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Frequently asked questions</h2>
            <p class="lx-section-head__subtitle">Answers to the most common customer questions.</p>
        </div>
        <div class="lx-faq__list">
            <div class="lx-faq__item">
                <h3 class="lx-faq__question">How can I get started?</h3>
                <p class="lx-faq__answer">Just register and start immediately. No credit card required.</p>
            </div>
            <div class="lx-faq__item">
                <h3 class="lx-faq__question">Is there a free version?</h3>
                <p class="lx-faq__answer">Yes, we offer a free plan with no time limit.</p>
            </div>
            <div class="lx-faq__item">
                <h3 class="lx-faq__question">How does customer support work?</h3>
                <p class="lx-faq__answer">Email support is available on business days 9am–5pm.</p>
            </div>
            <div class="lx-faq__item">
                <h3 class="lx-faq__question">Can I cancel my subscription at any time?</h3>
                <p class="lx-faq__answer">Yes, without contractual obligations or hidden fees.</p>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
