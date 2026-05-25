<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('causer.avatar')
                    ->label('')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn ($record): string => $record->causer
                        ? 'https://ui-avatars.com/api/?name='.urlencode($record->causer->name).'&color=ffffff&background=6366f1'
                        : 'https://ui-avatars.com/api/?name=?&color=ffffff&background=6b7280'
                    )
                    ->size(36),
                TextColumn::make('causer.name')
                    ->label(__('common.user'))
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('common.action'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('subject_type')
                    ->label(__('common.model'))
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('subject_name')
                    ->label(__('common.title'))
                    ->getStateUsing(function ($record): string {
                        $changes = $record->attribute_changes;
                        $attrs = $changes['attributes'] ?? $changes['old'] ?? [];

                        return $attrs['title'] ?? $attrs['name'] ?? $attrs['key'] ?? '-';
                    })
                    ->sortable(false),
                TextColumn::make('subject_id')
                    ->label(__('common.id'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('common.when'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('description')
                    ->label(__('common.action'))
                    ->options([
                        'created' => __('common.created'),
                        'updated' => __('common.updated'),
                        'deleted' => __('common.deleted'),
                    ]),
            ]);
    }
}
