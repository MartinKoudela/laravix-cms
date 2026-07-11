<?php

namespace Laravix\Cms\Filament\Resources\ContentTypeFields\Pages;

use Laravix\Cms\Filament\Resources\ContentTypeFields\ContentTypeFieldResource;
use Filament\Resources\Pages\CreateRecord;

class CreateContentTypeField extends CreateRecord
{
    protected static string $resource = ContentTypeFieldResource::class;
}
