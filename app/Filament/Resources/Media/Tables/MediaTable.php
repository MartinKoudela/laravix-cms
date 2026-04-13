<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('path')
                    ->label('Preview')
                    ->disk('public')
                    ->visibility(fn ($record) => str_starts_with($record->mime_type ?? '', 'image/')),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('site.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('mime_type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('size')
                    ->formatStateUsing(fn (int $state): string => number_format($state / 1024, 1) . ' KB')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
