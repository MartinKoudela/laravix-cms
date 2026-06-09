<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class AccordionBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('accordion')
            ->label('blocks.gjs.accordion')
            ->icon('fa-bars-staggered')
            ->category('blocks.categories.info')
            ->canvasHtml(<<<'HTML'
<section class="lx-section">
    <div class="lx-container--md">
        <h2 class="lx-section-head__title" style="text-align:center;margin-bottom:40px;">Frequently asked questions</h2>
        <details class="lx-accordion__item">
            <summary class="lx-accordion__summary"><span>How can I get started?</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
            <p class="lx-accordion__body">Just register and start immediately. No credit card required.</p>
        </details>
        <details class="lx-accordion__item">
            <summary class="lx-accordion__summary"><span>Is there a free version?</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
            <p class="lx-accordion__body">Yes, we offer a free plan with no time limit.</p>
        </details>
        <details class="lx-accordion__item">
            <summary class="lx-accordion__summary"><span>How does customer support work?</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
            <p class="lx-accordion__body">Email support is available on business days 9am–5pm.</p>
        </details>
        <details class="lx-accordion__item">
            <summary class="lx-accordion__summary"><span>Can I cancel my subscription at any time?</span><i class="fa-solid fa-chevron-down faq-chevron"></i></summary>
            <p class="lx-accordion__body">Yes, without contractual obligations or hidden fees.</p>
        </details>
    </div>
</section>
HTML);
    }
}
