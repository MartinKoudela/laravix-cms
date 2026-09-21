<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Policies;

use Laravix\Cms\Models\Role;
use Laravix\Cms\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasTenantPermission('roles.manage');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasSitePermission($role->site, 'roles.manage');
    }

    public function create(User $user): bool
    {
        return $user->hasTenantPermission('roles.manage');
    }

    public function update(User $user, Role $role): bool
    {
        return ! $role->isAdminRole()
            && $user->hasSitePermission($role->site, 'roles.manage');
    }

    public function delete(User $user, Role $role): bool
    {
        return ! $role->is_system
            && $user->hasSitePermission($role->site, 'roles.manage');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasTenantPermission('roles.manage');
    }
}
