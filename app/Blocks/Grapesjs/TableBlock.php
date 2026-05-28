<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class TableBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('table')
            ->label('blocks.gjs.table')
            ->icon('fa-table')
            ->category('blocks.categories.elements')
            ->canvasHtml(<<<'HTML'
<div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;font-size:.9375rem;">
        <thead>
            <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb;">
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151;">Column 1</th>
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151;">Column 2</th>
                <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151;">Column 3</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom:1px solid #e5e7eb;">
                <td style="padding:12px 16px;color:#374151;">Row 1</td>
                <td style="padding:12px 16px;color:#374151;">Data</td>
                <td style="padding:12px 16px;color:#374151;">Data</td>
            </tr>
            <tr style="border-bottom:1px solid #e5e7eb;">
                <td style="padding:12px 16px;color:#374151;">Row 2</td>
                <td style="padding:12px 16px;color:#374151;">Data</td>
                <td style="padding:12px 16px;color:#374151;">Data</td>
            </tr>
            <tr>
                <td style="padding:12px 16px;color:#374151;">Row 3</td>
                <td style="padding:12px 16px;color:#374151;">Data</td>
                <td style="padding:12px 16px;color:#374151;">Data</td>
            </tr>
        </tbody>
    </table>
</div>
HTML);
    }
}
