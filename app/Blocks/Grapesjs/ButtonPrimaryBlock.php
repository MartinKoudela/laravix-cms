<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks\Grapesjs;

use App\Support\BlockDefinition;

class ButtonPrimaryBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('button-primary')
            ->label('blocks.gjs.button')
            ->icon('fa-hand-pointer')
            ->category('blocks.categories.elements')
            ->canvasHtml('<a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;background:#111827;color:#fff;border:2px solid #111827;">Button text</a>');
    }
}
