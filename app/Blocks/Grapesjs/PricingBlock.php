<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class PricingBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('pricing')
            ->label('blocks.gjs.pricing')
            ->icon('fa-tag')
            ->category('blocks.categories.conversion')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;background:#f9fafb;">
    <div style="max-width:1000px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 12px;">Simple pricing</h2>
        <p style="font-size:1rem;color:#6b7280;text-align:center;margin:0 0 48px;">Choose the plan that suits you. No hidden fees.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
            <div style="background:#fff;border-radius:16px;padding:32px;border:1px solid #e5e7eb;">
                <h3 style="font-size:.875rem;font-weight:600;color:#6b7280;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Starter</h3>
                <p style="font-size:2.5rem;font-weight:800;color:#111827;margin:0 0 4px;">$0</p>
                <p style="font-size:.875rem;color:#9ca3af;margin:0 0 24px;">/ month</p>
                <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> 1 website</li>
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> 5 pages</li>
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#9ca3af;"><i class="fa-solid fa-xmark" style="color:#d1d5db;"></i> Custom domain</li>
                </ul>
                <a href="#" style="display:block;text-align:center;padding:12px;background:#f9fafb;color:#111827;font-weight:600;border-radius:8px;text-decoration:none;border:1px solid #e5e7eb;">Start for free</a>
            </div>
            <div style="background:#111827;border-radius:16px;padding:32px;">
                <h3 style="font-size:.875rem;font-weight:600;color:#9ca3af;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Pro</h3>
                <p style="font-size:2.5rem;font-weight:800;color:#fff;margin:0 0 4px;">$19</p>
                <p style="font-size:.875rem;color:#6b7280;margin:0 0 24px;">/ month</p>
                <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> 5 websites</li>
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> Unlimited pages</li>
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;color:#e5e7eb;"><i class="fa-solid fa-check" style="color:#4ade80;"></i> Custom domain</li>
                </ul>
                <a href="#" style="display:block;text-align:center;padding:12px;background:#2563eb;color:#fff;font-weight:600;border-radius:8px;text-decoration:none;">Choose Pro</a>
            </div>
            <div style="background:#fff;border-radius:16px;padding:32px;border:1px solid #e5e7eb;">
                <h3 style="font-size:.875rem;font-weight:600;color:#6b7280;margin:0 0 12px;text-transform:uppercase;letter-spacing:.05em;">Enterprise</h3>
                <p style="font-size:2.5rem;font-weight:800;color:#111827;margin:0 0 4px;">Custom</p>
                <p style="font-size:.875rem;color:#9ca3af;margin:0 0 24px;">by agreement</p>
                <ul style="list-style:none;padding:0;margin:0 0 28px;display:flex;flex-direction:column;gap:10px;">
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> Unlimited websites</li>
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> SLA support</li>
                    <li style="display:flex;align-items:center;gap:8px;font-size:.9375rem;"><i class="fa-solid fa-check" style="color:#16a34a;"></i> Custom integrations</li>
                </ul>
                <a href="#" style="display:block;text-align:center;padding:12px;background:#f9fafb;color:#111827;font-weight:600;border-radius:8px;text-decoration:none;border:1px solid #e5e7eb;">Contact us</a>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
