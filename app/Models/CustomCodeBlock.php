<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['site_id','name', 'icon', 'html_content', 'css_content', 'js_content' ])]
class CustomCodeBlock extends Model
{
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
