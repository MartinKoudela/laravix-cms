<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Taxonomies\Tables;

use Laravix\Cms\Support\TaxonomyTypeRegistry;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TaxonomiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->columnToggleFormMaxHeight('400')
            ->columns([
                TextColumn::make('name')
                    ->label(__('common.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('common.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => TaxonomyTypeRegistry::label($state))
                    ->sortable(),
                TextColumn::make('site.name')
                    ->label(__('common.site'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label(__('common.parent'))
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('slug')
                    ->label(__('common.slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'category' => __('taxonomy.types.category'),
                        'tag' => __('taxonomy.types.tag'),
                    ]),
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
