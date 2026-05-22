<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use App\Enums\SiteRole;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SiteUser extends Pivot
{
    protected function casts(): array
    {
        return [
            'role' => SiteRole::class,
        ];
    }
}
