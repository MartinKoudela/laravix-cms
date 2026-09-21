<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Policies;

use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

class MediaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasTenantPermission('media.view');
    }

    public function view(User $user, Media $media): bool
    {
        return $user->hasSitePermission($media->site, 'media.view');
    }

    public function create(User $user, ?Site $site = null): bool
    {
        if ($site instanceof Site) {
            return $user->hasSitePermission($site, 'media.create');
        }

        return $user->hasTenantPermission('media.create');
    }

    public function update(User $user, Media $media): bool
    {
        return $user->hasSitePermission($media->site, 'media.update');
    }

    public function delete(User $user, Media $media): bool
    {
        return $user->hasSitePermission($media->site, 'media.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasTenantPermission('media.delete');
    }
}
