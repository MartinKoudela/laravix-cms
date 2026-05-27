<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'page' => Tab::make(__('content.types.page'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'page'))
                ->badge(fn () => static::getResource()::getEloquentQuery()->where('type', 'page')->count()),
            'post' => Tab::make(__('content.types.post'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'post'))
                ->badge(fn () => static::getResource()::getEloquentQuery()->where('type', 'post')->count()),
            'archive' => Tab::make(__('content.types.archive'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'archive'))
                ->badge(fn () => static::getResource()::getEloquentQuery()->where('type', 'archive')->count()),
        ];
    }
}
