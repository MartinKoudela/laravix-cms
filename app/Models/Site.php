<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'domain', 'theme'])]
class Site extends Model
{
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
            ->withPivot('role')
            ->withTimestamps();
    }

}
