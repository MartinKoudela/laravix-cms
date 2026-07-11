<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks;

use Laravix\Cms\Support\BlockDefinition;
use Laravix\Cms\Support\BlockRegistry;
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
            ->gjsIcon('fa-table-columns')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;">
    <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:48px;align-items:center;">
        <div>
            <h2 style="font-size:2rem;font-weight:700;color:#111827;margin:0 0 16px;">Left column</h2>
            <p style="font-size:1rem;color:#374151;line-height:1.8;margin:0;">Left column text.</p>
        </div>
        <div>
            <img src="https://placehold.co/600x400?text=Photo" data-gjs-type="media-image" style="width:100%;border-radius:12px;display:block;" alt="">
        </div>
    </div>
</section>
HTML)
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
