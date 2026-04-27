<?php

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
            ->label('Cards')
            ->icon('heroicon-o-squares-2x2')
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('Heading'))->columnSpanFull(),
                Repeater::make('items')
                    ->label(fn () => __('Cards'))
                    ->schema([
                        TextInput::make('title')->label(fn () => __('Title')),
                        FieldComponentFactory::mediaSelect('image_id', __('Image')),
                        TextInput::make('link')->label(fn () => __('Link'))->url(),
                        Builder::make('blocks')
                            ->blocks(BlockRegistry::toBlocks())
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
