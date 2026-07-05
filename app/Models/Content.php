<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\ContentObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['site_id', 'type', 'locale', 'translation_group_id', 'title', 'slug', 'is_homepage', 'blocks', 'grapesjs_data', 'grapesjs_html', 'status', 'published_at', 'created_by'])]
#[ObservedBy(ContentObserver::class)]
class Content extends Model
{
    use HasFactory, LogsActivity, Recyclable, SoftDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()
            ->useLogName('site-'.$this->site_id);

    }

    protected function casts(): array
    {
        return [
            'is_homepage' => 'boolean',
            'published_at' => 'datetime',
            'status' => ContentStatus::class,
            'blocks' => 'array',
        ];
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Taxonomy::class, 'content_taxonomy');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(ContentField::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(ContentRevision::class)->latest();
    }

    public function redirects(): HasMany
    {
        return $this->hasMany(Redirect::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Content::class, 'translation_group_id', 'translation_group_id')
            ->whereKeyNot($this->getKey());
    }

    public function translationForLocale(string $locale): ?Content
    {
        return $this->locale === $locale
            ? $this
            : $this->translations()->where('locale', $locale)->first();
    }

    public function path(string $defaultLocale): string
    {
        $path = $this->is_homepage ? '/' : '/'.$this->slug;

        if ($this->locale && $this->locale !== $defaultLocale) {
            $path = '/'.$this->locale.($path === '/' ? '' : $path);
        }

        return $path;
    }
}
