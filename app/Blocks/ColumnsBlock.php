<?php

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
            ->label('Columns')
            ->icon('heroicon-o-view-columns')
            ->nestable(false)
            ->schema(fn () => [
                Select::make('columns_count')
                    ->label(fn () => __('Number of Columns'))
                    ->options(fn () => ['2' => '2', '3' => '3', '4' => '4'])
                    ->default('2')
                    ->required(),
                Repeater::make('columns')
                    ->label(fn () => __('Columns'))
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
