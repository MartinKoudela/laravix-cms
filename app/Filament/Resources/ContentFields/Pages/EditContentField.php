<?php

namespace App\Filament\Resources\ContentFields\Pages;

use App\Filament\Resources\ContentFields\ContentFieldResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContentField extends EditRecord
{
    protected static string $resource = ContentFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
