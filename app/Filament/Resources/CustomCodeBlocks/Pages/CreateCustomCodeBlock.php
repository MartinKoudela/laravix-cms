<?php

namespace App\Filament\Resources\CustomCodeBlocks\Pages;

use App\Filament\Resources\CustomCodeBlocks\CustomCodeBlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomCodeBlock extends CreateRecord
{
    protected static string $resource = CustomCodeBlockResource::class;
}
