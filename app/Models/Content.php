<?php

namespace App\Models;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Promethys\Revive\Concerns\Recyclable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Observers\ContentObserver;

#[ObservedBy(ContentObserver::class)]
#[Fillable(['site_id', 'type', 'title', 'slug', 'is_homepage', 'blocks', 'status', 'published_at', 'created_by'])]
class Content extends Model
{
    use LogsActivity, HasFactory, SoftDeletes, Recyclable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()
            ->useLogName('site-' . $this->site_id);

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

}
