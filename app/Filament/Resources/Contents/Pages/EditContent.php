<?php

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use App\Models\Content;
use App\Support\FieldRegistry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditContent extends EditRecord
{
    protected static string $resource = ContentResource::class;

    protected array $fieldData = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->url(function (): string {
                    /** @var Content $record */
                    $record = $this->getRecord();
                    $record->loadMissing('site');

                    $slug = $record->is_homepage ? '' : ltrim($record->slug, '/');

                    return 'https://'.$record->site->domain.'/'.$slug;
                }, shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $fields = $this->record->fields()->pluck('value', 'key');

        foreach ($fields as $key => $value) {
            $data['field_'.$key] = $value;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $definitions = FieldRegistry::forContentType($this->record->type);

        foreach ($definitions as $definition) {
            $this->fieldData[$definition->key] = $data['field_'.$definition->key] ?? null;
            unset($data['field_'.$definition->key]);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        foreach ($this->fieldData as $key => $value) {
            $this->record->fields()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
