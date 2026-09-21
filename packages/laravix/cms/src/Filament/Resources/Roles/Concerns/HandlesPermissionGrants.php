<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Roles\Concerns;

use Illuminate\Validation\ValidationException;
use Laravix\Cms\Filament\Resources\Roles\Schemas\RoleForm;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Support\PermissionDefinition;

trait HandlesPermissionGrants
{
    protected function groupPermissions(array $data): array
    {
        $permissions = $data['permissions'] ?? [];

        foreach (RoleForm::grantableGroups() as $group => $definitions) {
            $data['grants'][$group] = array_values(array_filter(
                array_map(fn (PermissionDefinition $definition): string => $definition->key, $definitions),
                fn (string $key): bool => in_array($key, $permissions, true),
            ));
        }

        return $data;
    }

    protected function flattenPermissions(array $data, array $existing = []): array
    {
        $selected = array_merge(...array_values(array_map(
            fn (mixed $keys): array => is_array($keys) ? $keys : [],
            $data['grants'] ?? [],
        )) ?: [[]]);

        $this->assertGrantable($selected);

        $grantable = array_merge(...array_values(array_map(
            fn (array $definitions): array => array_map(fn (PermissionDefinition $definition): string => $definition->key, $definitions),
            RoleForm::grantableGroups(),
        )) ?: [[]]);

        $hidden = array_values(array_filter($existing, fn (string $key): bool => ! in_array($key, $grantable, true)));

        unset($data['grants']);
        $data['permissions'] = array_values(array_unique([...$hidden, ...$selected]));

        return $data;
    }

    protected function assertGrantable(array $selected): void
    {
        $user = auth()->user();
        $tenant = filament()->getTenant();
        $site = $tenant instanceof Site ? $tenant : null;

        foreach ($selected as $permission) {
            if (! $user?->hasSitePermission($site, $permission)) {
                throw ValidationException::withMessages([
                    'data.grants' => __('laravix::roles.messages.cannot_grant', ['permission' => $permission]),
                ]);
            }
        }
    }
}
