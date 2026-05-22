<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Policies;

use App\Enums\SiteRole;
use App\Models\Site;
use App\Models\Taxonomy;
use App\Models\User;

class TaxonomyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Taxonomy $taxonomy): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return $user->roleForSite($taxonomy->site) !== null;
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

    public function update(User $user, Taxonomy $taxonomy): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return in_array($user->roleForSite($taxonomy->site),
            [SiteRole::ADMIN, SiteRole::EDITOR]);
    }

    public function delete(User $user, Taxonomy $taxonomy): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        return $user->roleForSite($taxonomy->site) === SiteRole::ADMIN;
    }
}
