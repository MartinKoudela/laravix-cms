<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['site_id', 'name', 'token', 'prefix', 'expires_at'])]
class SiteApiToken extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logExcept(['token'])->logOnlyDirty()
            ->useLogName('site-'.$this->site_id);
    }

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public static function newPlaintextToken(): string
    {
        return 'lvx_'.Str::random(40);
    }

    public static function hashToken(string $plaintext): string
    {
        return hash('sha256', $plaintext);
    }

    public static function generateFor(Site $site, string $name, ?CarbonInterface $expiresAt = null): array
    {
        $plaintext = static::newPlaintextToken();

        $token = static::create([
            'site_id' => $site->id,
            'name' => $name,
            'token' => static::hashToken($plaintext),
            'prefix' => substr($plaintext, 0, 12),
            'expires_at' => $expiresAt,
        ]);

        return ['token' => $token, 'plaintext' => $plaintext];
    }

    public function isExpired(): bool
    {
        return $this->expires_at?->isPast() ?? false;
    }

    public function markAsUsed(): void
    {
        if ($this->last_used_at === null || $this->last_used_at->lt(now()->subMinute())) {
            $this->forceFill(['last_used_at' => now()])->saveQuietly();
        }
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
