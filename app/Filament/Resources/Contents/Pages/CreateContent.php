<?php

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use App\Support\AppearanceRegistry;
use App\Support\FieldRegistry;
use Filament\Resources\Pages\CreateRecord;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;

    protected array $fieldData = [];

    protected array $appearanceData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $type = $data['type'] ?? null;

        foreach (FieldRegistry::forContentType($type) as $definition) {
            $this->fieldData[$definition->key] = $data['field_'.$definition->key] ?? null;
            unset($data['field_'.$definition->key]);
        }

        foreach (AppearanceRegistry::forContentType($type) as $definition) {
            $this->appearanceData[$definition->key] = $data['appearance_'.$definition->key] ?? null;
            unset($data['appearance_'.$definition->key]);
        }

        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->fieldData as $key => $value) {
            $this->record->fields()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        foreach ($this->appearanceData as $key => $value) {
            $this->record->fields()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
