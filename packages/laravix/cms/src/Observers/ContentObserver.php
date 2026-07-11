<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Observers;

use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Events\ContentCreated;
use Laravix\Cms\Events\ContentDeleted;
use Laravix\Cms\Events\ContentPublished;
use Laravix\Cms\Events\ContentRestored;
use Laravix\Cms\Events\ContentUnpublished;
use Laravix\Cms\Events\ContentUpdated;
use Laravix\Cms\Models\Content;

class ContentObserver
{
    public function creating(Content $content): void
    {
        if (! $content->locale) {
            $content->locale = $content->site?->defaultLocale() ?? 'en';
        }
    }

    public function saving(Content $content): void
    {
        if ($content->isDirty(['title', 'grapesjs_html', 'blocks'])) {
            $content->search_text = $content->computeSearchText();
        }
    }

    public function created(Content $content): void
    {
        if (! $content->translation_group_id) {
            $content->updateQuietly(['translation_group_id' => $content->id]);
        }

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
