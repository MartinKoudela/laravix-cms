<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class ContactFormBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('contact-form')
            ->label('blocks.gjs.contact_form')
            ->icon('fa-envelope')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;background:#f9fafb;">
    <div style="max-width:560px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 8px;">Contact us</h2>
        <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 36px;">We respond within 24 hours on business days.</p>
        <form data-contact-form style="display:flex;flex-direction:column;gap:16px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">Name</label><input name="name" type="text" placeholder="John Smith" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;" required></div>
                <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">E-mail</label><input name="email" type="email" placeholder="john@example.com" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;" required></div>
            </div>
            <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">Subject</label><input name="subject" type="text" placeholder="Question about..." style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;box-sizing:border-box;"></div>
            <div><label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin:0 0 6px;">Message</label><textarea name="message" rows="5" placeholder="Your message..." style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.9375rem;resize:vertical;box-sizing:border-box;" required></textarea></div>
            <div id="form-status" style="display:none;padding:12px;border-radius:8px;font-size:.9375rem;text-align:center;"></div>
            <button type="submit" style="padding:14px;background:#111827;color:#fff;font-weight:600;border:none;border-radius:8px;font-size:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;"><i class="fa-solid fa-paper-plane"></i> Send message</button>
        </form>
    </div>
</section>
HTML);
    }
}
