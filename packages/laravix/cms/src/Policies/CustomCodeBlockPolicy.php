<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Policies;

use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\CustomCodeBlock;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

class CustomCodeBlockPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CustomCodeBlock $block): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return $user->roleForSite($block->site) !== null;
    }

    public function create(User $user): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        $site = filament()->getTenant();

        return $site instanceof Site
            && in_array($user->roleForSite($site), [SiteRole::ADMIN, SiteRole::EDITOR]);
    }

    public function deleteAny(User $user): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        $site = filament()->getTenant();

        return $site instanceof Site
            && $user->roleForSite($site) === SiteRole::ADMIN;
    }

    public function update(User $user, CustomCodeBlock $block): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return in_array($user->roleForSite($block->site),
            [SiteRole::ADMIN, SiteRole::EDITOR]);
    }

    public function delete(User $user, CustomCodeBlock $block): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return $user->roleForSite($block->site) === SiteRole::ADMIN;
    }
}
