<?php

namespace App\Filament\Resources\CustomCodeBlocks\Pages;

use App\Filament\Resources\CustomCodeBlocks\CustomCodeBlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomCodeBlocks extends ListRecords
{
    protected static string $resource = CustomCodeBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
