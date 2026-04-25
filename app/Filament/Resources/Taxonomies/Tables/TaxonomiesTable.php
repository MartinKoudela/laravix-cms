<?php

namespace App\Filament\Resources\Taxonomies\Tables;

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
            ->columnToggleFormMaxHeight('400')
            ->columns([
                TextColumn::make('name')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('site.name')
                    ->label(__('Site'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label(__('Parent'))
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'category' => 'Category',
                        'tag' => 'Tag',
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
