<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Taxonomies;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Laravix\Cms\Filament\Resources\Taxonomies\Pages\CreateTaxonomy;
use Laravix\Cms\Filament\Resources\Taxonomies\Pages\EditTaxonomy;
use Laravix\Cms\Filament\Resources\Taxonomies\Pages\ListTaxonomies;
use Laravix\Cms\Filament\Resources\Taxonomies\Schemas\TaxonomyForm;
use Laravix\Cms\Filament\Resources\Taxonomies\Tables\TaxonomiesTable;
use Laravix\Cms\Models\Taxonomy;

class TaxonomyResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('laravix::taxonomy.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('laravix::taxonomy.plural');
    }

    protected static ?string $model = Taxonomy::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TaxonomyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxonomiesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTaxonomies::route('/'),
            'create' => CreateTaxonomy::route('/create'),
            'edit' => EditTaxonomy::route('/{record}/edit'),
        ];
    }
}
