<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Laravix\Cms\Support\BlockDefinition;

class ButtonBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('button')
            ->label('laravix::blocks.types.button')
            ->icon('heroicon-o-cursor-arrow-rays')
            ->schema(fn () => [
                TextInput::make('label')->label(fn () => __('laravix::common.label')),
                TextInput::make('url')->label(fn () => __('laravix::common.url'))->url(),
                Select::make('style')
                    ->label(fn () => __('laravix::blocks.fields.style'))
                    ->options(fn () => [
                        'primary' => __('laravix::blocks.styles.primary'),
                        'secondary' => __('laravix::blocks.styles.secondary'),
                        'outline' => __('laravix::blocks.styles.outline'),
                    ])
                    ->default('primary'),
            ]);
    }
}
