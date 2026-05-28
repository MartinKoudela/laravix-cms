<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class StatsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('stats')
            ->label('blocks.gjs.stats')
            ->icon('fa-chart-bar')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;background:#111827;">
    <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:32px;text-align:center;">
        <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">10,000+</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Happy customers</p></div>
        <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">99.9%</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Service uptime</p></div>
        <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">50+</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Countries worldwide</p></div>
        <div><p style="font-size:3rem;font-weight:800;color:#fff;margin:0 0 4px;">24/7</p><p style="font-size:.9375rem;color:#9ca3af;margin:0;">Customer support</p></div>
    </div>
</section>
HTML);
    }
}
