<?php

namespace App\Filament\Resources\Sites\Tables;

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
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('domain')
                    ->label(__('Domain'))
                    ->searchable()
                    ->url(fn ($record) => 'https://'.$record->domain)
                    ->openUrlInNewTab(),
                TextColumn::make('theme')
                    ->label(__('Theme'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('users_count')
                    ->label(__('Users'))
                    ->counts('users')
                    ->sortable(),
                TextColumn::make('contents_count')
                    ->label(__('Contents'))
                    ->counts('contents')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
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
