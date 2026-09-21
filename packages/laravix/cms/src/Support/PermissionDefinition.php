<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Laravix\Cms\Enums\SiteRole;

class PermissionDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $group,
        public readonly array $defaultRoles = [],
    ) {}

    public static function make(string $key): static
    {
        [$group] = explode('.', $key, 2);

        return new static($key, $key, $group);
    }

    public function label(string $label): static
    {
        return new static($this->key, $label, $this->group, $this->defaultRoles);
    }

    public function group(string $group): static
    {
        return new static($this->key, $this->label, $group, $this->defaultRoles);
    }

    public function grantTo(array $roles): static
    {
        return new static($this->key, $this->label, $this->group, $roles);
    }

    public function isGrantedByDefaultTo(SiteRole $role): bool
    {
        return in_array($role, $this->defaultRoles, true);
    }
}
