<?php

namespace App\Filament\Resources\Taxonomies;

use App\Filament\Resources\Taxonomies\Pages\CreateTaxonomy;
use App\Filament\Resources\Taxonomies\Pages\EditTaxonomy;
use App\Filament\Resources\Taxonomies\Pages\ListTaxonomies;
use App\Filament\Resources\Taxonomies\Schemas\TaxonomyForm;
use App\Filament\Resources\Taxonomies\Tables\TaxonomiesTable;
use App\Models\Taxonomy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxonomyResource extends Resource
{
    protected static ?string $model = Taxonomy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TaxonomyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxonomiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
