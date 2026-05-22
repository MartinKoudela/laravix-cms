<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use App\Enums\RedirectStatusCode;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['content_id', 'old_url', 'status_code'])]
class Redirect extends Model
{

    protected function casts(): array
    {
        return [
            'status_code' => RedirectStatusCode::class,
        ];
    }


    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

}
