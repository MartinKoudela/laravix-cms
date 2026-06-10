<?php

namespace App\Filament\Resources\CustomCodeBlocks\Pages;

use App\Filament\Resources\CustomCodeBlocks\CustomCodeBlockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomCodeBlock extends EditRecord
{
    protected static string $resource = CustomCodeBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
