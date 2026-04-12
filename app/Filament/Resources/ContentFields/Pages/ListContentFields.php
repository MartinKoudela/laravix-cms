<?php

namespace App\Filament\Resources\ContentFields\Pages;

use App\Filament\Resources\ContentFields\ContentFieldResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentFields extends ListRecords
{
    protected static string $resource = ContentFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
