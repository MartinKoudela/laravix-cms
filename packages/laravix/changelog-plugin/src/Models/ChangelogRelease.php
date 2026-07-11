<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravix\Cms\Models\Site;

class ChangelogRelease extends Model
{
    protected $fillable = ['site_id', 'version', 'title', 'translations', 'released_at'];

    protected function casts(): array
    {
        return [
            'released_at' => 'date',
            'translations' => 'array',
        ];
    }

    public function localizedTitle(?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return $this->translations[$locale]['title'] ?? $this->title;
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ChangelogItem::class)->orderBy('sort_order');
    }
}
