<?php

namespace App\Filament\Resources\ContentTypeFields\Pages;

use App\Filament\Resources\ContentTypeFields\ContentTypeFieldResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentTypeField extends CreateRecord
{
    protected static string $resource = ContentTypeFieldResource::class;
}
