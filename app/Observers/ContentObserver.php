<?php

namespace App\Observers;

use App\Models\Content;

class ContentObserver
{
    /**
     * Handle the Content "created" event.
     */
    public function created(Content $content): void
    {
        $content->revisions()->create([
            'data' => [
                'title' => $content->title,
                'slug' => $content->slug,
                'status' => $content->status->value,
                'is_homepage' => $content->is_homepage,
                'published_at' => $content->published_at,
                'blocks' => $content->blocks,
                'fields' => $content->fields()->pluck('value', 'key')->toArray(),
                'created_by' => auth()->id(),
            ],
        ]);
    }

    /**
     * Handle the Content "updated" event.
     */
    public function updated(Content $content): void
    {
        $content->revisions()->create([
            'data' => [
                'title' => $content->title,
                'slug' => $content->slug,
                'status' => $content->status->value,
                'is_homepage' => $content->is_homepage,
                'published_at' => $content->published_at,
                'blocks' => $content->blocks,
                'fields' => $content->fields()->pluck('value', 'key')->toArray(),
                'created_by' => auth()->id(),
            ],
        ]);
    }

    /**
     * Handle the Content "deleted" event.
     */
    public function deleted(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "restored" event.
     */
    public function restored(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "force deleted" event.
     */
    public function forceDeleted(Content $content): void
    {
        //
    }
}
