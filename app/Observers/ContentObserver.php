<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Observers;

use App\Enums\ContentStatus;
use App\Events\ContentCreated;
use App\Events\ContentDeleted;
use App\Events\ContentPublished;
use App\Events\ContentRestored;
use App\Events\ContentUnpublished;
use App\Events\ContentUpdated;
use App\Models\Content;

class ContentObserver
{
    public function created(Content $content): void
    {
        ContentCreated::dispatch($content);

        if ($content->status === ContentStatus::PUBLISHED) {
            ContentPublished::dispatch($content);
        }
    }

    public function updated(Content $content): void
    {
        ContentUpdated::dispatch($content);

        if (! $content->wasChanged('status')) {
            return;
        }

        if ($content->status === ContentStatus::PUBLISHED) {
            ContentPublished::dispatch($content);
        } elseif ($content->getOriginal('status') === ContentStatus::PUBLISHED) {
            ContentUnpublished::dispatch($content);
        }
    }

    public function deleted(Content $content): void
    {
        ContentDeleted::dispatch($content);
    }

    public function restored(Content $content): void
    {
        ContentRestored::dispatch($content);
    }
}
