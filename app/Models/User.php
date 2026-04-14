<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
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
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, LogsActivity, Notifiable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class,
            'site_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_super_admin || $this->sites()->exists();
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->is_super_admin) {
            return Site::all();
        }

        return $this->sites;
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
        ];
    }
}
