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
    protected static ?string $heading = 'Recent Content';

    protected ?string $pollingInterval = null;
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
                    ->searchable(),
                TextColumn::make('site.name')
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        ContentStatus::Published->value => 'success',
                        ContentStatus::Scheduled->value => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('updated_at')
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
