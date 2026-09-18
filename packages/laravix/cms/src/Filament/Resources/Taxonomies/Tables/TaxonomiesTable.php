<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Taxonomies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Layout\Component as Layout;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Laravix\Cms\Filament\Actions\HoverActions;
use Laravix\Cms\Filament\Actions\ReorderRecordsAction;
use Laravix\Cms\Filament\Actions\ShowTaxonomyContentsAction;
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
            ->recordActions(HoverActions::wrap([
                EditAction::make(),
                ShowTaxonomyContentsAction::make(),
                ReorderRecordsAction::make(),
                DeleteAction::make(),
            ]))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function gridColumns(): array
    {
        return [
            Stack::make([
                TextColumn::make('name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->color('gray')
                    ->size('xs')
                    ->placeholder('—'),
                Split::make([
                    TextColumn::make('type')
                        ->badge()
                        ->formatStateUsing(fn (string $state): string => TaxonomyTypeRegistry::label($state))
                        ->grow(false),
                    TextColumn::make('contents_count')
                        ->counts('contents')
                        ->formatStateUsing(fn (int $state): string => trans_choice('laravix::taxonomy.contents_count', $state, ['count' => $state]))
                        ->color('gray')
                        ->size('xs')
                        ->alignEnd(),
                ]),
            ])->space(2),
        ];
    }
}
