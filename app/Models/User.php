<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use App\Enums\SiteRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'avatar', 'fast_actions'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar, HasTenants
{
    use HasFactory, Notifiable;

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar) {
            return Storage::disk('public')->url($this->avatar);
        }

        return $this->generateAvatarSvg();
    }

    private function generateAvatarSvg(): string
    {
        $initials = collect(explode(' ', $this->name))
            ->map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
            ->take(2)
            ->join('');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#FF0C5D"/><stop offset="100%" style="stop-color:#FF6602"/></linearGradient></defs><rect width="100" height="100" fill="url(#g)"/><text x="50" y="50" font-family="sans-serif" font-size="40" font-weight="bold" fill="white" text-anchor="middle" dominant-baseline="central">'.htmlspecialchars($initials).'</text></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'site_user')
            ->using(SiteUser::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_super_admin || $this->sites()->exists();
    }

    public function isAdmin(): bool
    {
        return $this->is_super_admin || $this->sites()
            ->wherePivot('role', SiteRole::ADMIN)
            ->exists();
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->is_super_admin) {
            return Site::query()->get();
        }

        return $this->sites;
    }

    public function roleForSite(Site $site): ?SiteRole
    {
        return $this->sites()
            ->whereKey($site)
            ->first()
            ?->pivot
            ->role;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->is_super_admin) {
            return true;
        }

        return $this->sites()->whereKey($tenant)->exists();
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class,
            'created_by');
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class,
            'created_by');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'fast_actions' => 'array',
        ];
    }
}
