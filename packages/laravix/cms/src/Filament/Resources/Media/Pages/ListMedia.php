<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Media\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Laravix\Cms\Enums\TableLayout;
use Laravix\Cms\Filament\Concerns\HasTableLayoutSwitcher;
use Laravix\Cms\Filament\Resources\Media\MediaResource;
use Laravix\Cms\Filament\Resources\Media\Tables\MediaTable;

class ListMedia extends ListRecords
{
    use HasTableLayoutSwitcher;

    protected static string $resource = MediaResource::class;

    public function table(Table $table): Table
    {
        return $this->applyTableLayout(parent::table($table));
    }

    protected static function defaultTableLayout(): TableLayout
    {
        return TableLayout::Grid;
    }

    protected static function gridColumns(): array
    {
        return MediaTable::gridColumns();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
