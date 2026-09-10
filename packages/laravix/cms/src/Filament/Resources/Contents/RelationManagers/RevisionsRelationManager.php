<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Contents\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Laravix\Cms\Models\ContentRevision;
use Laravix\Cms\Support\ContentRevisionRestorer;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            // Builder saves used to write a revision of their own shape, which
            // carries no snapshot to restore. Those rows stay in the database
            // but are no longer offered here.
            ->modifyQueryUsing(fn ($query) => $query->whereNull('data->source')->select([
                'id',
                'content_id',
                'created_by',
                'created_at',
                'updated_at',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.title')) as revision_title"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) as revision_status"),
            ]))
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable()->label(__('laravix::common.date')),
                TextColumn::make('author.name')->label(__('laravix::common.author'))->default('—'),
                TextColumn::make('revision_title')->label(__('laravix::common.title'))->default('—'),
                TextColumn::make('revision_status')->label(__('laravix::common.status'))->badge()->default('—'),
            ])
            ->headerActions([])
            ->recordActions([
                Action::make('revert')
                    ->label(__('laravix::content.actions.revert'))
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire, ContentRevisionRestorer $restorer) {
                        $restorer->restore(ContentRevision::findOrFail($record->id));

                        $livewire->redirect(request()->header('Referer'));
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
