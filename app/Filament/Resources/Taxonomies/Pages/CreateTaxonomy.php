<?php

namespace App\Filament\Resources\Taxonomies\Pages;

use App\Filament\Resources\Taxonomies\TaxonomyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaxonomy extends CreateRecord
{
    protected static string $resource = TaxonomyResource::class;
}
