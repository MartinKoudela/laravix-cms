<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Policies;

use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\User;

class ContentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasTenantPermission('content.view');
    }

    public function view(User $user, Content $content): bool
    {
        return $user->hasSitePermission($content->site, 'content.view');
    }

    public function create(User $user): bool
    {
        return $user->hasTenantPermission('content.create');
    }

    public function update(User $user, Content $content): bool
    {
        return $user->hasSitePermission($content->site, 'content.update');
    }

    public function delete(User $user, Content $content): bool
    {
        return $user->hasSitePermission($content->site, 'content.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasTenantPermission('content.delete');
    }

    public function publish(User $user, Content $content): bool
    {
        return $user->hasSitePermission($content->site, 'content.publish');
    }
}
