<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Models;

use Laravix\Cms\Enums\SiteMode;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Promethys\Revive\Concerns\Recyclable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['name', 'domain', 'mode', 'theme', 'locales', 'navigations', 'nav_design'])]
class Site extends Model implements HasAvatar
{
    use HasFactory, LogsActivity, Recyclable, SoftDeletes;

    public function getFilamentAvatarUrl(): ?string
    {
        $faviconId = $this->settings()
            ->where('key', 'favicon')
            ->value('value');

        if (! $faviconId) {
            return null;
        }

        return $this->media()->find($faviconId)?->url;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()
            ->useLogName('site-'.$this->id);

    }

    protected function casts(): array
    {
        return [
            'mode' => SiteMode::class,
            'locales' => 'array',
            'navigations' => 'array',
            'nav_design' => 'array',
        ];
    }

    public function defaultLocale(): string
    {
        return $this->settings()->where('key', 'locale')->value('value') ?: 'en';
    }

    public function enabledLocales(): array
    {
        return array_values(array_unique(array_merge([$this->defaultLocale()], $this->locales ?? [])));
    }

    public function isMultilingual(): bool
    {
        return count($this->enabledLocales()) > 1;
    }

    public static function availableContentLocales(): array
    {
        return [
            'en' => 'English',
            'cs' => 'Čeština',
            'sk' => 'Slovenčina',
            'de' => 'Deutsch',
            'fr' => 'Français',
            'es' => 'Español',
            'it' => 'Italiano',
            'pl' => 'Polski',
            'pt' => 'Português',
            'uk' => 'Українська',
            'nl' => 'Nederlands',
            'hu' => 'Magyar',
            'ro' => 'Română',
            'sv' => 'Svenska',
            'tr' => 'Türkçe',
            'ja' => '日本語',
            'zh' => '中文',
        ];
    }

    public function isHeadless(): bool
    {
        return $this->mode === SiteMode::HEADLESS;
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function taxonomies(): HasMany
    {
        return $this->hasMany(Taxonomy::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function apiTokens(): HasMany
    {
        return $this->hasMany(SiteApiToken::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'site_user')
            ->using(SiteUser::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function setDomainAttribute(string $value): void
    {
        $this->attributes['domain'] = rtrim(parse_url($value, PHP_URL_HOST) ?? $value, '/');
    }

    public static function availableThemes(): array
    {
        return collect(glob(base_path('themes/*'), GLOB_ONLYDIR))
            ->mapWithKeys(fn ($path) => [basename($path) => Str::headline(basename($path))])
            ->all();
    }

    public static function themePreviewPath(string $theme): ?string
    {
        foreach (['svg', 'webp', 'png', 'jpg'] as $extension) {
            $path = base_path("themes/{$theme}/preview.{$extension}");

            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function themePreviewUrl(string $theme): ?string
    {
        return static::themePreviewPath($theme) ? route('theme.preview', ['theme' => $theme]) : null;
    }

    public static function themeOptionLabel(string $theme): string
    {
        $label = e(static::availableThemes()[$theme] ?? Str::headline($theme));
        $preview = static::themePreviewUrl($theme);

        $image = $preview
            ? '<img src="'.e($preview).'" style="width:160px;height:100px;object-fit:cover;border-radius:8px;flex-shrink:0;border:1px solid rgba(0,0,0,.1)">'
            : '<div style="width:160px;height:100px;border-radius:8px;flex-shrink:0;background:#f3f4f6;display:flex;align-items:center;justify-content:center;color:#9ca3af;font-size:12px">'.$label.'</div>';

        return '<div style="display:flex;align-items:center;gap:16px;padding:8px 0">'.$image.'<span style="font-weight:500;font-size:14px">'.$label.'</span></div>';
    }
}
