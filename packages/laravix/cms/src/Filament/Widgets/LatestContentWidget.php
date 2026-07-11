<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Widgets;

use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Filament\Resources\Contents\ContentResource;
use Laravix\Cms\Models\Content;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestContentWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    public function getTableHeading(): string
    {
        return __('content.stats.recent');
    }

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Content::query()
                    ->with(['site', 'author'])
                    ->latest('updated_at')
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('title')
                    ->label(__('common.title'))
                    ->searchable(),
                TextColumn::make('site.name')
                    ->label(__('common.site'))
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('common.type'))
                    ->badge(),
                TextColumn::make('status')
                    ->label(__('common.status'))
                    ->badge()
                    ->color(fn (ContentStatus $state): string => match ($state) {
                        ContentStatus::PUBLISHED => 'success',
                        ContentStatus::SCHEDULED => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (ContentStatus $state): string => $state->value),
                TextColumn::make('updated_at')
                    ->label(__('common.updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Content $record): string => ContentResource::getUrl('edit', ['record' => $record]))
                    ->icon(Heroicon::OutlinedPencil),
            ])
            ->paginated(false);
    }
}
