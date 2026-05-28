<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class LogoBarBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('logo-bar')
            ->label('blocks.gjs.logo_bar')
            ->icon('fa-building')
            ->category('blocks.categories.social_proof')
            ->canvasHtml(<<<'HTML'
<section style="padding:40px 24px;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;">
    <div style="max-width:960px;margin:0 auto;">
        <p style="font-size:.875rem;font-weight:500;color:#9ca3af;text-align:center;margin:0 0 28px;text-transform:uppercase;letter-spacing:.08em;">Trusted by</p>
        <div style="display:flex;align-items:center;justify-content:center;gap:48px;flex-wrap:wrap;">
            <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Company A</span>
            <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Company B</span>
            <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Company C</span>
            <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Company D</span>
            <span style="font-size:1.375rem;font-weight:800;color:#d1d5db;letter-spacing:-.02em;">Company E</span>
        </div>
    </div>
</section>
HTML);
    }
}
