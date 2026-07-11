<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Models;

use Laravix\Cms\Enums\FieldType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['site_id', 'content_type', 'key', 'label', 'type', 'group', 'hint', 'config', 'required', 'sort_order'])]
class ContentTypeField extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => FieldType::class,
            'config' => 'array',
            'required' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        $clearCache = fn (self $field) => cache()->forget("field_registry:{$field->site_id}:{$field->content_type}");

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
