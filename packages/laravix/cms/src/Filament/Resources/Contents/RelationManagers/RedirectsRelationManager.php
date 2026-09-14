<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Contents\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Laravix\Cms\Enums\RedirectStatusCode;

class RedirectsRelationManager extends RelationManager
{
    protected static string $relationship = 'redirects';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('laravix::content.relations.redirects');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('old_url')
                    ->required()
                    ->maxLength(255),
                Select::make('status_code')
                    ->options(RedirectStatusCode::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('old_url')
            ->columns([
                TextColumn::make('old_url')
                    ->searchable(),
                TextColumn::make('status_code')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
