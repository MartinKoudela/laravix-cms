<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        $data['disk'] = 'public';

        if (isset($data['path'])) {
            $file = Storage::disk('public')->path($data['path']);
            $data['mime_type'] = mime_content_type($file) ?: 'application/octet-stream';
            $data['size'] = filesize($file) ?: 0;
        }

        return $data;
    }
}
