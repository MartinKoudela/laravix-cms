<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class StepsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('steps')
            ->label('blocks.gjs.steps')
            ->icon('fa-list-ol')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:900px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">How it works</h2>
        <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 48px;">Three simple steps to the result.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:32px;">
            <div style="text-align:center;">
                <div style="width:56px;height:56px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.25rem;font-weight:700;color:#2563eb;">1</div>
                <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Registration</h3>
                <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Create your account in 30 seconds.</p>
            </div>
            <div style="text-align:center;">
                <div style="width:56px;height:56px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.25rem;font-weight:700;color:#2563eb;">2</div>
                <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Setup</h3>
                <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Customize your environment.</p>
            </div>
            <div style="text-align:center;">
                <div style="width:56px;height:56px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:1.25rem;font-weight:700;color:#2563eb;">3</div>
                <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0 0 8px;">Launch</h3>
                <p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Publish and track results.</p>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
