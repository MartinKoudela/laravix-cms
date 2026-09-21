<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Roles\Pages;

use Laravix\Cms\Filament\Pages\BaseCreateRecord;
use Laravix\Cms\Filament\Resources\Roles\Concerns\HandlesPermissionGrants;
use Laravix\Cms\Filament\Resources\Roles\RoleResource;

class CreateRole extends BaseCreateRecord
{
    use HandlesPermissionGrants;

    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->flattenPermissions($data);
    }
}
