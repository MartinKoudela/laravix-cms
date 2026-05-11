<?php

namespace App\Blocks;

use App\Support\BlockDefinition;

class DividerBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('divider')
            ->label('blocks.types.divider')
            ->icon('heroicon-o-minus')
            ->schema([]);
    }
}
