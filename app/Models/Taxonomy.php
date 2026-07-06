<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['site_id', 'type', 'name', 'slug', 'parent_id', 'sort_order'])]
class Taxonomy extends Model
{
    use HasFactory, LogsActivity, Recyclable, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()
            ->useLogName('site-'.$this->site_id);

    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Taxonomy::class, 'parent_id');
    }

    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'content_taxonomy');
    }
}
