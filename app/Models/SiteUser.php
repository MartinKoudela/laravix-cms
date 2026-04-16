<?php

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
