<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Support\PermissionRegistry;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['site_id', 'name', 'slug', 'permissions', 'is_system'])]
class Role extends Model
{
    use HasFactory, LogsActivity;

    public const string WILDCARD = '*';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()
            ->useLogName('site-'.$this->site_id);
    }

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_system' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $role): void {
            $role->site_id ??= filament()->getTenant()?->getKey();
            $role->slug = $role->slug ?: static::uniqueSlugFor((int) $role->site_id, $role->name);
            $role->permissions = static::normalizePermissions($role->permissions ?? []);
        });

        static::updating(function (self $role): void {
            $role->permissions = static::normalizePermissions($role->permissions ?? []);
        });
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeForSite(Builder $query, Site|int $site): void
    {
        $query->where('site_id', $site instanceof Site ? $site->id : $site);
    }

    public static function findBySlug(Site|int $site, string $slug): ?static
    {
        return static::query()->forSite($site)->where('slug', $slug)->first();
    }

    public function allows(string $permission): bool
    {
        $permissions = $this->permissions ?? [];

        return in_array(static::WILDCARD, $permissions, true)
            || in_array($permission, $permissions, true);
    }

    public function isAdminRole(): bool
    {
        return $this->slug === SiteRole::ADMIN->value;
    }

    public function isAssignableBy(User $user): bool
    {
        if ($user->is_super_admin) {
            return true;
        }

        $site = $this->site;

        if (! $site instanceof Site) {
            return false;
        }

        if (in_array(static::WILDCARD, $this->permissions ?? [], true)) {
            return $user->hasSitePermission($site, static::WILDCARD);
        }

        foreach ($this->permissions ?? [] as $permission) {
            if (! $user->hasSitePermission($site, $permission)) {
                return false;
            }
        }

        return true;
    }

    public static function assignableOptions(User $user, Site $site): array
    {
        return static::query()
            ->forSite($site)
            ->orderByDesc('is_system')
            ->orderBy('name')
            ->get()
            ->filter(fn (self $role): bool => $role->isAssignableBy($user))
            ->mapWithKeys(fn (self $role): array => [$role->slug => $role->name])
            ->all();
    }

    public static function optionsForSite(Site $site): array
    {
        return static::query()
            ->forSite($site)
            ->orderByDesc('is_system')
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->all();
    }

    public function usersCount(): int
    {
        return $this->site->users()->wherePivot('role', $this->slug)->count();
    }

    public function pendingInvitationsCount(): int
    {
        return UserInvitation::query()
            ->where('site_id', $this->site_id)
            ->where('role', $this->slug)
            ->pending()
            ->count();
    }

    public function isInUse(): bool
    {
        return $this->usersCount() > 0 || $this->pendingInvitationsCount() > 0;
    }

    public static function seedSystemRolesFor(Site $site): void
    {
        foreach (SiteRole::cases() as $role) {
            static::query()->firstOrCreate(
                ['site_id' => $site->id, 'slug' => $role->value],
                [
                    'name' => ucfirst($role->value),
                    'permissions' => $role->defaultPermissions(),
                    'is_system' => true,
                ],
            );
        }
    }

    public static function normalizePermissions(array $permissions): array
    {
        if (in_array(static::WILDCARD, $permissions, true)) {
            return [static::WILDCARD];
        }

        return array_values(array_unique(array_filter(
            $permissions,
            fn (mixed $permission): bool => is_string($permission) && PermissionRegistry::has($permission),
        )));
    }

    public static function uniqueSlugFor(int $siteId, string $name): string
    {
        $base = Str::slug($name) ?: 'role';
        $slug = $base;
        $suffix = 2;

        while (static::query()->forSite($siteId)->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
