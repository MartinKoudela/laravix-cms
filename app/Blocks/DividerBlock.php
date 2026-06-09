<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks;

use App\Support\BlockDefinition;

class DividerBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('divider')
            ->label('blocks.types.divider')
            ->icon('heroicon-o-minus')
            ->gjsIcon('fa-minus')
            ->schema(fn () => [])
            ->canvasHtml('<div style="padding:32px 24px;"><hr style="border:none;border-top:1px solid #e5e7eb;"></div>')
            ->category('blocks.categories.info');
    }
}
