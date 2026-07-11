<?php

namespace Laravix\Cms\Filament\Resources\ContentTypeFields\Pages;

use Laravix\Cms\Filament\Resources\ContentTypeFields\ContentTypeFieldResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentTypeField extends EditRecord
{
    protected static string $resource = ContentTypeFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
