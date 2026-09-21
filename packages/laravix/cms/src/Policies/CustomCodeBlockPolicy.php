<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Policies;

use Laravix\Cms\Models\CustomCodeBlock;
use Laravix\Cms\Models\User;

class CustomCodeBlockPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasTenantPermission('custom_code_blocks.view');
    }

    public function view(User $user, CustomCodeBlock $block): bool
    {
        return $user->hasSitePermission($block->site, 'custom_code_blocks.view');
    }

    public function create(User $user): bool
    {
        return $user->hasTenantPermission('custom_code_blocks.create');
    }

    public function update(User $user, CustomCodeBlock $block): bool
    {
        return $user->hasSitePermission($block->site, 'custom_code_blocks.update');
    }

    public function delete(User $user, CustomCodeBlock $block): bool
    {
        return $user->hasSitePermission($block->site, 'custom_code_blocks.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasTenantPermission('custom_code_blocks.delete');
    }
}
