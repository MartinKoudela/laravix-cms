<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravix\Cms\Models\Site;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'name' => ucfirst(fake()->unique()->word()),
            'permissions' => ['content.view'],
            'is_system' => false,
        ];
    }

    public function withPermissions(array $permissions): static
    {
        return $this->state(fn (): array => ['permissions' => $permissions]);
    }
}
