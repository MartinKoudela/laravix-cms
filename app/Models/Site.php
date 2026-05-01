<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['name', 'domain', 'theme', 'navigations'])]
class Site extends Model implements HasAvatar
{
    use HasFactory, LogsActivity;

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
            'navigations' => 'array',
        ];
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
            ->mapWithKeys(fn ($path) => [basename($path) => basename($path)])
            ->all();
    }
}
