<?php

namespace App\Filament\Widgets;

use App\Enums\ContentStatus;
use App\Filament\Resources\Contents\ContentResource;
use App\Models\Content;
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
        return __('Recent Content');
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
                    ->label(__('Title'))
                    ->searchable(),
                TextColumn::make('site.name')
                    ->label(__('Site'))
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (ContentStatus $state): string => match ($state) {
                        ContentStatus::PUBLISHED => 'success',
                        ContentStatus::SCHEDULED => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (ContentStatus $state): string => $state->value),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
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
