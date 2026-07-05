<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Events;

use App\Models\Content;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Content $content) {}
}
