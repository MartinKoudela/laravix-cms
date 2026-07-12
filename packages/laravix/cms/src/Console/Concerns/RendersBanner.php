<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Concerns;

use Laravix\Cms\Laravix;
use Symfony\Component\Console\Terminal;

trait RendersBanner
{
    private function renderBanner(): void
    {
        $version = Laravix::version();

        if ((new Terminal)->getWidth() < 70) {
            $this->line('<fg=#ff0465;options=bold>Laravix CMS</> <fg=#888888>'.$version.'</>');

            return;
        }

        $art = [
            '   __                      _     ',
            '  / /  ___ ________ __  __(_)_ __',
            ' / /__/ _ `/ __/ _ `/ |/ / /\\ \\ /',
            '/____/\\_,_/_/  \\_,_/|___/_//_\\_\\ ',
        ];

        $info = [
            ['Version', $version],
            ['Website', $this->hyperlink('https://laravix.com', 'laravix.com')],
            ['Docs', $this->hyperlink('https://laravix.com/docs', 'laravix.com/docs')],
            ['GitHub', $this->hyperlink('https://github.com/Laravix/cms', 'github.com/Laravix/cms')],
        ];

        $this->newLine();

        foreach ($art as $i => $line) {
            [$label, $value] = $info[$i];

            $this->line('<fg=#ff0465;options=bold>'.$line.'</>   <fg=#888888>'.str_pad($label, 7).'</>  '.$value);
        }

        $this->newLine();
    }

    private function hyperlink(string $url, ?string $label = null): string
    {
        $label ??= $url;

        if (! $this->output->isDecorated()) {
            return $label;
        }

        return "\033]8;;{$url}\033\\{$label}\033]8;;\033\\";
    }
}
