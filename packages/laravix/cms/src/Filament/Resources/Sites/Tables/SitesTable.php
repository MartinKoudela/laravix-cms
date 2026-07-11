<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Sites\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('laravix::common.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('domain')
                    ->label(__('laravix::common.domain'))
                    ->searchable()
                    ->url(fn ($record) => 'https://'.$record->domain)
                    ->openUrlInNewTab(),
                TextColumn::make('theme')
                    ->label(__('laravix::common.theme'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('users_count')
                    ->label(__('laravix::sites.fields.users_count'))
                    ->counts('users')
                    ->sortable(),
                TextColumn::make('contents_count')
                    ->label(__('laravix::sites.fields.contents_count'))
                    ->counts('contents')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('laravix::common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
