<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Taxonomies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Laravix\Cms\Support\TaxonomyTypeRegistry;

class TaxonomiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columnToggleFormMaxHeight('400')
            ->columns([
                TextColumn::make('sort_order')
                    ->label(__('laravix::content_type_field.fields.sort_order'))
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('laravix::common.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('laravix::common.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => TaxonomyTypeRegistry::label($state))
                    ->sortable(),
                TextColumn::make('site.name')
                    ->label(__('laravix::common.site'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label(__('laravix::common.parent'))
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('slug')
                    ->label(__('laravix::common.slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('laravix::common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(fn () => TaxonomyTypeRegistry::options()),
                SelectFilter::make('site')
                    ->relationship('site', 'name'),
            ])
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
