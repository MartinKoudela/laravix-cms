<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Component as Layout;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Laravix\Cms\Filament\Actions\CopyMediaUrlAction;
use Laravix\Cms\Filament\Actions\HoverActions;
use Laravix\Cms\Filament\Actions\OpenMediaAction;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('path')
                        ->disk(fn ($record) => $record->disk)
                        ->imageSize(56)
                        ->square()
                        ->extraImgAttributes(['loading' => 'lazy'])
                        ->defaultImageUrl(fn ($record): string => 'https://ui-avatars.com/api/?name='.urlencode(pathinfo($record->name ?? 'file', PATHINFO_EXTENSION)).'&size=56&background=e2e8f0&color=64748b&bold=true&length=3')
                        ->grow(false),
                    Stack::make([
                        TextColumn::make('name')
                            ->label(__('laravix::common.title'))
                            ->weight(FontWeight::Medium)
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('mime_type')
                            ->badge()
                            ->color('gray')
                            ->searchable(),
                    ]),
                    Stack::make([
                        TextColumn::make('size')
                            ->label(__('laravix::common.size'))
                            ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB')
                            ->color('gray')
                            ->sortable(),
                    ])->visibleFrom('md'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions(HoverActions::wrap([
                EditAction::make(),
                OpenMediaAction::make(),
                CopyMediaUrlAction::make(),
                DeleteAction::make(),
            ]))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<Column|Layout>
     */
    public static function gridColumns(): array
    {
        return [
            Stack::make([
                ImageColumn::make('path')
                    ->disk(fn ($record) => $record->disk)
                    ->imageHeight('10rem')
                    ->imageWidth('100%')
                    ->extraImgAttributes(['loading' => 'lazy', 'class' => 'object-cover rounded-lg'])
                    ->defaultImageUrl(fn ($record): string => 'https://ui-avatars.com/api/?name='.urlencode(pathinfo($record->name ?? 'file', PATHINFO_EXTENSION)).'&size=256&background=e2e8f0&color=64748b&bold=true&length=3'),
                TextColumn::make('name')
                    ->weight(FontWeight::Medium)
                    ->limit(40)
                    ->searchable()
                    ->sortable(),
                Split::make([
                    TextColumn::make('mime_type')
                        ->badge()
                        ->color('gray')
                        ->grow(false),
                    TextColumn::make('size')
                        ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB')
                        ->color('gray')
                        ->size('xs')
                        ->alignEnd(),
                ]),
            ])->space(2),
        ];
    }
}
