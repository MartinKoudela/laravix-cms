<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Concerns;

use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

trait CtaReference
{
    private function offerPromo(): void
    {
        if (! confirm('Building on Laravix? Get a free shout-out — we can feature your site as a reference on laravix.com.', default: false)) {
            return;
        }

        $url = 'https://laravix.com/reference';

        info("Nice! Opening {$url}");

        $this->openInBrowser($url);
    }

    private function openInBrowser(string $url): void
    {
        $command = match (PHP_OS_FAMILY) {
            'Darwin' => ['open', $url],
            'Windows' => ['cmd', '/c', 'start', '', $url],
            default => ['xdg-open', $url],
        };

        Process::run($command);
    }
}
