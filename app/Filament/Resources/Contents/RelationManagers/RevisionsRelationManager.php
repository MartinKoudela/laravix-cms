<?php

namespace App\Filament\Resources\Contents\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable()->label('Date'),
                TextColumn::make('author.name')->label('Author')->default('—'),
                TextColumn::make('title')->label('Title')
                    ->getStateUsing(fn ($record) => $record->data['title'] ?? '—'),
                TextColumn::make('status')->label('Status')
                    ->getStateUsing(fn ($record) => $record->data['status'] ?? '—')
                    ->badge(),
            ])
            ->headerActions([])
            ->recordActions([
                Action::make('revert')
                    ->label('Revert')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record, $livewire) {
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
