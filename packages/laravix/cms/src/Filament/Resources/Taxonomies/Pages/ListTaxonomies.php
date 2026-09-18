<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Taxonomies\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Laravix\Cms\Filament\Concerns\HasTableLayoutSwitcher;
use Laravix\Cms\Filament\Resources\Taxonomies\Tables\TaxonomiesTable;
use Laravix\Cms\Filament\Resources\Taxonomies\TaxonomyResource;

class ListTaxonomies extends ListRecords
{
    use HasTableLayoutSwitcher;

    protected static string $resource = TaxonomyResource::class;

    public function table(Table $table): Table
    {
        return $this->applyTableLayout(parent::table($table));
    }

    protected static function gridColumns(): array
    {
        return TaxonomiesTable::gridColumns();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
