<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Console\Commands;

use App\Enums\ContentStatus;
use App\Models\Content;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('cms:publish-scheduled')]
#[Description('Publish scheduled content whose published_at date has passed.')]
class PublishScheduledContent extends Command
{
    public function handle(): int
    {
        $contents = Content::where('status', ContentStatus::SCHEDULED->value)
            ->where('published_at', '<=', now())
            ->get();

        foreach ($contents as $content) {
            $content->update(['status' => ContentStatus::PUBLISHED]);
        }

        $this->info("Published {$contents->count()} scheduled content item(s).");

        return self::SUCCESS;
    }
}
