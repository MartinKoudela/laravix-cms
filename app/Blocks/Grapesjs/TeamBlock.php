<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class TeamBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('team')
            ->label('blocks.gjs.team')
            ->icon('fa-people-group')
            ->category('blocks.categories.social_proof')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:1000px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">Our team</h2>
        <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 40px;">The people behind the project.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:24px;">
            <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">John Smith</p><p style="font-size:.875rem;color:#6b7280;margin:0;">CEO &amp; Founder</p></div>
            <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">Jane Doe</p><p style="font-size:.875rem;color:#6b7280;margin:0;">CTO</p></div>
            <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">Mike Johnson</p><p style="font-size:.875rem;color:#6b7280;margin:0;">Lead Developer</p></div>
            <div style="text-align:center;"><img src="https://placehold.co/200x200?text=Photo" data-gjs-type="media-image" style="width:100px;height:100px;border-radius:50%;margin:0 auto 12px;display:block;object-fit:cover;" alt=""><p style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 4px;">Sarah Brown</p><p style="font-size:.875rem;color:#6b7280;margin:0;">Head of Design</p></div>
        </div>
    </div>
</section>
HTML);
    }
}
