<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravix\Cms\Support\ThemeManifest;

#[Signature('laravix:theme:link {--force : Replace links that already exist}')]
#[Description('Expose every theme dist/ directory under public/themes so the browser can reach it.')]
class LinkThemes extends Command
{
    public function handle(): int
    {
        $root = public_path('themes');

        if (! is_dir($root)) {
            mkdir($root, 0755, true);
        }

        $linked = 0;

        ThemeManifest::flush();

        foreach (ThemeManifest::all() as $theme) {
            $target = $theme->path('dist');
            $link = $root.'/'.$theme->key;

            if (! is_dir($target)) {
                continue;
            }

            if (is_link($link)) {
                if (! $this->option('force') && readlink($link) === $target) {
                    continue;
                }

                unlink($link);
            } elseif (file_exists($link)) {
                $this->components->error("public/themes/{$theme->key} exists and is not a link — remove it by hand.");

                continue;
            }

            symlink($target, $link);
            $this->components->task("public/themes/{$theme->key}");
            $linked++;
        }

        if ($linked === 0) {
            $this->components->info('No theme ships a dist/ directory — nothing to link.');
        }

        return self::SUCCESS;
    }
}
