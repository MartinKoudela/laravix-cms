<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Enums;

use Laravix\Cms\Models\Role;
use Laravix\Cms\Support\PermissionRegistry;

enum SiteRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case VIEWER = 'viewer';

    public function label(): string
    {
        return __('laravix::users.roles.'.$this->value);
    }

    public function defaultPermissions(): array
    {
        return match ($this) {
            self::ADMIN => [Role::WILDCARD],
            default => PermissionRegistry::defaultsFor($this),
        };
    }

    public static function isSystem(string $slug): bool
    {
        return self::tryFrom($slug) !== null;
    }
}
