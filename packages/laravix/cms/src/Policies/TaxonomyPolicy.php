<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Policies;

use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Models\User;

class TaxonomyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasTenantPermission('taxonomies.view');
    }

    public function view(User $user, Taxonomy $taxonomy): bool
    {
        return $user->hasSitePermission($taxonomy->site, 'taxonomies.view');
    }

    public function create(User $user): bool
    {
        return $user->hasTenantPermission('taxonomies.create');
    }

    public function update(User $user, Taxonomy $taxonomy): bool
    {
        return $user->hasSitePermission($taxonomy->site, 'taxonomies.update');
    }

    public function delete(User $user, Taxonomy $taxonomy): bool
    {
        return $user->hasSitePermission($taxonomy->site, 'taxonomies.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasTenantPermission('taxonomies.delete');
    }
}
