<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks;

use App\Support\BlockDefinition;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

class TextBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('text')
            ->label('blocks.types.text')
            ->icon('heroicon-o-document-text')
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('common.heading'))->columnSpanFull(),
                RichEditor::make('content')->label(fn () => __('common.content'))->columnSpanFull(),
            ]);
    }
}
