<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Contents\RelationManagers;

use Laravix\Cms\Models\ContentRevision;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            ->modifyQueryUsing(fn ($query) => $query->select([
                'id',
                'content_id',
                'created_by',
                'created_at',
                'updated_at',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.title')) as revision_title"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.status')) as revision_status"),
            ]))
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable()->label(__('common.date')),
                TextColumn::make('author.name')->label(__('common.author'))->default('—'),
                TextColumn::make('revision_title')->label(__('common.title'))->default('—'),
                TextColumn::make('revision_status')->label(__('common.status'))->badge()->default('—'),
            ])
            ->headerActions([])
            ->recordActions([
                Action::make('revert')
                    ->label(__('content.actions.revert'))
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
                        $record = ContentRevision::find($record->id);
                        $content = $record->content;
                        $data = $record->data;

                        $content->update([
                            'title' => $data['title'],
                            'slug' => $data['slug'],
                            'status' => $data['status'],
                            'is_homepage' => $data['is_homepage'],
                            'published_at' => $data['published_at'],
                            'blocks' => $data['blocks'],
                        ]);

                        foreach ($data['fields'] as $key => $value) {
                            $content->fields()->updateOrCreate(['key' => $key], ['value' => $value]);
                        }
                        $content->fields()->whereNotIn('key', array_keys($data['fields']))->delete();

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
