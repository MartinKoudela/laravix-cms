<?php

namespace App\Filament\Resources\ContentTypeFields\Pages;

use App\Filament\Resources\ContentTypeFields\ContentTypeFieldResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentTypeFields extends ListRecords
{
    protected static string $resource = ContentTypeFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
