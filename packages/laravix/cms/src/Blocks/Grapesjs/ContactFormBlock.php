<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class ContactFormBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('contact-form')
            ->label('blocks.gjs.contact_form')
            ->icon('fa-envelope')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-container--form">
        <div class="lx-section-head">
            <h2 class="lx-section-head__title">Contact us</h2>
            <p class="lx-section-head__subtitle">We respond within 24 hours on business days.</p>
        </div>
        <form data-contact-form class="lx-contact-form__form">
            <div class="lx-contact-form__row">
                <div>
                    <label class="lx-contact-form__label">Name</label>
                    <input name="name" type="text" placeholder="John Smith" required class="lx-contact-form__input">
                </div>
                <div>
                    <label class="lx-contact-form__label">E-mail</label>
                    <input name="email" type="email" placeholder="john@example.com" required class="lx-contact-form__input">
                </div>
            </div>
            <div>
                <label class="lx-contact-form__label">Subject</label>
                <input name="subject" type="text" placeholder="Question about..." class="lx-contact-form__input">
            </div>
            <div>
                <label class="lx-contact-form__label">Message</label>
                <textarea name="message" rows="5" placeholder="Your message..." required class="lx-contact-form__textarea"></textarea>
            </div>
            <div id="form-status" class="lx-contact-form__status"></div>
            <button type="submit" class="lx-contact-form__submit">
                <i class="fa-solid fa-paper-plane"></i> Send message
            </button>
        </form>
    </div>
</section>
HTML);
    }
}
