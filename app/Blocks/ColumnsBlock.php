<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Blocks;

use App\Support\BlockDefinition;
use App\Support\BlockRegistry;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class ColumnsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('columns')
            ->label('blocks.types.columns')
            ->icon('heroicon-o-view-columns')
            ->nestable(false)
            ->schema(fn () => [
                Select::make('columns_count')
                    ->label(fn () => __('blocks.fields.number_of_columns'))
                    ->options(fn () => ['2' => '2', '3' => '3', '4' => '4'])
                    ->default('2')
                    ->required(),
                Repeater::make('columns')
                    ->label(fn () => __('blocks.fields.columns'))
                    ->schema([
                        Builder::make('blocks')
                            ->blocks(BlockRegistry::toNestableBlocks())
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
