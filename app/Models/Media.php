<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use App\Enums\ImageVariant;
use App\Observers\ImageTransformationObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Promethys\Revive\Concerns\Recyclable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['site_id', 'name', 'path', 'disk', 'mime_type', 'size', 'created_by', 'variants'])]
#[ObservedBy(ImageTransformationObserver::class)]
class Media extends Model
{
    use HasFactory, LogsActivity, Recyclable, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()
            ->useLogName('site-'.$this->site_id);

    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class,
            'created_by');
    }

    public function variantUrl(ImageVariant $variant): string
    {
        $path = $this->variants[$variant->value] ?? null;

        if ($path === null) {
            return $this->url;
        }

        return Storage::disk($this->disk)->url($path);
    }
}
