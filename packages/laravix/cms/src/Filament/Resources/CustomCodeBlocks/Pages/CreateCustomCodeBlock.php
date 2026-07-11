<?php

namespace Laravix\Cms\Filament\Resources\CustomCodeBlocks\Pages;

use Laravix\Cms\Filament\Resources\CustomCodeBlocks\CustomCodeBlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomCodeBlock extends CreateRecord
{
    protected static string $resource = CustomCodeBlockResource::class;
}
