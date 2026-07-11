<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\ContentTypeFields\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Laravix\Cms\Enums\FieldType;
use Laravix\Cms\Support\ContentTypeRegistry;

class ContentTypeFieldsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label(__('laravix::content_type_field.fields.label'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('key')
                    ->label(__('laravix::content_type_field.fields.key'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('content_type')
                    ->label(__('laravix::content_type_field.fields.content_type'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('laravix::content_type_field.fields.type'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('group')
                    ->label(__('laravix::content_type_field.fields.group'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label(__('laravix::content_type_field.fields.sort_order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('required')
                    ->label(__('laravix::content_type_field.fields.required'))
                    ->boolean()
                    ->trueColor('warning')
                    ->falseColor('gray'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('content_type')
                    ->label(__('laravix::content_type_field.fields.content_type'))
                    ->options(fn () => ContentTypeRegistry::options()),
                SelectFilter::make('type')
                    ->label(__('laravix::content_type_field.fields.type'))
                    ->options(collect(FieldType::cases())->mapWithKeys(
                        fn (FieldType $case) => [$case->value => $case->name]
                    )),
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
