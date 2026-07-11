<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Contents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Filament\Actions\PreviewAction;

class ContentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->columnToggleFormMaxHeight('400px')
            ->columns([
                TextColumn::make('title')
                    ->label(__('laravix::common.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('laravix::common.slug'))
                    ->formatStateUsing(fn (string $state): string => str_starts_with($state, '/') ? $state : '/'.$state)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('site.name')
                    ->label(__('laravix::common.site'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('locale')
                    ->label(__('laravix::content.fields.locale'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => strtoupper($state ?? ''))
                    ->visible(fn (): bool => filament()->getTenant()?->isMultilingual() ?? false),
                TextColumn::make('status')
                    ->label(__('laravix::common.status'))
                    ->badge()
                    ->color(fn (ContentStatus $state): string => match ($state) {
                        ContentStatus::PUBLISHED => 'success',
                        ContentStatus::SCHEDULED => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (ContentStatus $state): string => $state->value)
                    ->sortable(),
                IconColumn::make('is_homepage')
                    ->label(__('laravix::common.homepage'))
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author.name')
                    ->label(__('laravix::common.author'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('laravix::common.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label(__('laravix::common.published_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('locale')
                    ->label(__('laravix::content.fields.locale'))
                    ->options(fn () => collect(filament()->getTenant()?->enabledLocales() ?? [])
                        ->mapWithKeys(fn (string $locale) => [$locale => strtoupper($locale)]))
                    ->visible(fn (): bool => filament()->getTenant()?->isMultilingual() ?? false),
                SelectFilter::make('status')
                    ->options(collect(ContentStatus::cases())->mapWithKeys(
                        fn (ContentStatus $case) => [$case->value => $case->name]
                    )),
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
