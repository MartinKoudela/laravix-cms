<?php

namespace Laravix\Cms\Filament\Resources\CustomCodeBlocks\Pages;

use Laravix\Cms\Filament\Resources\CustomCodeBlocks\CustomCodeBlockResource;
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
