<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Laravix\Cms\Support\BlockDefinition;

class TextBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('text')
            ->label('laravix::blocks.types.text')
            ->icon('heroicon-o-document-text')
            ->gjsIcon('fa-align-left')
            ->category('laravix::blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:720px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">Section heading</h2>
        <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0;">This is section text. Click to edit content.</p>
    </div>
</section>
HTML)
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('laravix::common.heading'))->columnSpanFull(),
                RichEditor::make('content')->label(fn () => __('laravix::common.content'))->columnSpanFull(),
            ]);
    }
}
