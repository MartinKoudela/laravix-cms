<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Policies;

use Laravix\Cms\Models\ContentTypeField;
use Laravix\Cms\Models\User;

class ContentTypeFieldPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasTenantPermission('content_type_fields.view');
    }

    public function view(User $user, ContentTypeField $field): bool
    {
        return $user->hasSitePermission($field->site, 'content_type_fields.view');
    }

    public function create(User $user): bool
    {
        return $user->hasTenantPermission('content_type_fields.create');
    }

    public function update(User $user, ContentTypeField $field): bool
    {
        return $user->hasSitePermission($field->site, 'content_type_fields.update');
    }

    public function delete(User $user, ContentTypeField $field): bool
    {
        return $user->hasSitePermission($field->site, 'content_type_fields.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasTenantPermission('content_type_fields.delete');
    }
}
