<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Roles\Pages;

use Filament\Actions\DeleteAction;
use Laravix\Cms\Filament\Pages\BaseEditRecord;
use Laravix\Cms\Filament\Resources\Roles\Concerns\HandlesPermissionGrants;
use Laravix\Cms\Filament\Resources\Roles\RoleResource;
use Laravix\Cms\Models\Role;

class EditRole extends BaseEditRecord
{
    use HandlesPermissionGrants;

    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->groupPermissions($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $role = $this->getRecord();

        return $this->flattenPermissions($data, $role->permissions ?? []);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            DeleteAction::make()
                ->visible(fn (Role $record): bool => ! $record->is_system && ! $record->isInUse()),
        ];
    }
}
