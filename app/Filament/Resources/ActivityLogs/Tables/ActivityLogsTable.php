<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('causer.name')
                    ->label(__('User'))
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('Action'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('subject_type')
                    ->label(__('Model'))
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('subject_name')
                    ->label(__('Title'))
                    ->getStateUsing(function ($record): string {
                        $changes = $record->attribute_changes;
                        $attrs = $changes['attributes'] ?? $changes['old'] ?? [];

                        return $attrs['title'] ?? $attrs['name'] ?? $attrs['key'] ?? '-';
                    })
                    ->sortable(false),
                TextColumn::make('subject_id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('When'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('description')
                    ->label('Action')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
            ]);
    }
}
