<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks;

use Laravix\Cms\Support\BlockDefinition;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ButtonBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('button')
            ->label('blocks.types.button')
            ->icon('heroicon-o-cursor-arrow-rays')
            ->schema(fn () => [
                TextInput::make('label')->label(fn () => __('common.label')),
                TextInput::make('url')->label(fn () => __('common.url'))->url(),
                Select::make('style')
                    ->label(fn () => __('blocks.fields.style'))
                    ->options(fn () => [
                        'primary' => __('blocks.styles.primary'),
                        'secondary' => __('blocks.styles.secondary'),
                        'outline' => __('blocks.styles.outline'),
                    ])
                    ->default('primary'),
            ]);
    }
}
