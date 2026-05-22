<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Contents\Tables;

use App\Enums\ContentStatus;
use App\Filament\Actions\PreviewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columnToggleFormMaxHeight('400px')
            ->columns([
                TextColumn::make('title')
                    ->label(__('common.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('common.slug'))
                    ->prefix('/')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('site.name')
                    ->label(__('common.site'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('common.type'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('common.status'))
                    ->badge()
                    ->color(fn (ContentStatus $state): string => match ($state) {
                        ContentStatus::PUBLISHED => 'success',
                        ContentStatus::SCHEDULED => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (ContentStatus $state): string => $state->value)
                    ->sortable(),
                IconColumn::make('is_homepage')
                    ->label(__('common.homepage'))
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author.name')
                    ->label(__('common.author'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('common.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label(__('common.published_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(collect(ContentStatus::cases())->mapWithKeys(
                        fn (ContentStatus $case) => [$case->value => $case->name]
                    )),
                SelectFilter::make('type')
                    ->options([
                        'page' => __('content.types.page'),
                        'post' => __('content.types.post'),
                    ]),
                SelectFilter::make('site')
                    ->relationship('site', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                PreviewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
