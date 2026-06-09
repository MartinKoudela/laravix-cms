<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class NewsletterBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('newsletter')
            ->label('blocks.gjs.newsletter')
            ->icon('fa-at')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--blue">
    <div class="lx-container--form" style="text-align:center;">
        <h2 class="lx-section-head__title">Stay informed</h2>
        <p class="lx-section-head__subtitle">News and tips straight to your email. No spam, unsubscribe anytime.</p>
        <form data-contact-form class="lx-newsletter__form">
            <input name="email" type="email" placeholder="your@email.com" required class="lx-newsletter__input">
            <button type="submit" class="lx-newsletter__btn">Subscribe</button>
        </form>
    </div>
</section>
HTML);
    }
}
