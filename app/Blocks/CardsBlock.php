<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks;

use App\Support\BlockDefinition;
use App\Support\BlockRegistry;
use App\Support\FieldComponentFactory;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class CardsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('cards')
            ->label('blocks.types.cards')
            ->icon('heroicon-o-squares-2x2')
            ->nestable(false)
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('common.heading'))->columnSpanFull(),
                Repeater::make('items')
                    ->label(fn () => __('blocks.fields.cards'))
                    ->schema([
                        TextInput::make('title')->label(fn () => __('common.title')),
                        FieldComponentFactory::mediaSelect('image_id', __('common.image')),
                        TextInput::make('link')->label(fn () => __('common.link'))->url(),
                        Builder::make('blocks')
                            ->blocks(BlockRegistry::toNestableBlocks())
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
