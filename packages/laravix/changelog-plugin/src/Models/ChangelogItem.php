<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangelogItem extends Model
{
    public const array TYPES = ['added', 'changed', 'fixed', 'removed', 'security'];

    protected $fillable = ['changelog_release_id', 'type', 'text', 'translations', 'sort_order'];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
        ];
    }

    public function localizedText(?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return $this->translations[$locale]['text'] ?? $this->text;
    }

    public function release(): BelongsTo
    {
        return $this->belongsTo(ChangelogRelease::class, 'changelog_release_id');
    }
}
