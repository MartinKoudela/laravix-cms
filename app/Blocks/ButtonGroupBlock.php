<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks;

use App\Support\BlockDefinition;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ButtonGroupBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('button_group')
            ->label('blocks.types.button_group')
            ->icon('heroicon-o-cursor-arrow-rays')
            ->schema(fn () => [
                Repeater::make('buttons')
                    ->label(fn () => __('blocks.fields.buttons'))
                    ->schema([
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
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
