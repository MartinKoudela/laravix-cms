<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                            ->label(__('common.title'))
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
                            ->label(__('common.size'))
                            ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1).' KB')
                            ->color('gray')
                            ->sortable(),
                    ])->visibleFrom('md'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
