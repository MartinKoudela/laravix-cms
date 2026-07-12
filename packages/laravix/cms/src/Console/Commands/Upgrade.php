<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Laravix\Cms\Laravix;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\confirm;

#[Signature('laravix:upgrade {--force : Skip the confirmation prompt}')]
#[Description('Upgrade the Laravix CMS core: composer update, migrations and asset republish.')]
class Upgrade extends Command
{
    public function handle(): int
    {
        $current = Laravix::version();

        $this->components->info("Current version: {$current}");

        if (! $this->option('force') && ! $this->option('no-interaction')
            && ! confirm('Back up your database before upgrading. Continue?', default: true)) {
            return self::FAILURE;
        }

        $composer = $this->composerCommand();

        if ($composer === null) {
            $this->components->error('Composer binary not found. Install composer or set the COMPOSER_BINARY environment variable.');

            return self::FAILURE;
        }

        $composerOutput = null;

        if (! $this->runStreaming([...$composer, 'update', 'laravix/cms', '--with-all-dependencies', '--no-interaction'], $composerOutput)) {
            $this->components->error('composer update failed — nothing else was touched.');

            return self::FAILURE;
        }

        $this->renderPackageChanges($composerOutput);

        foreach ([
            ['migrate', '--force'],
            ['vendor:publish', '--tag=laravix-assets', '--force'],
            ['filament:assets'],
            ['optimize:clear'],
        ] as $artisanCommand) {
            if (! $this->runStreaming([PHP_BINARY, 'artisan', ...$artisanCommand])) {
                $this->components->error('Command "'.implode(' ', $artisanCommand).'" failed — finish the upgrade manually.');

                return self::FAILURE;
            }
        }

        Cache::forget('laravix.latest-version');

        $new = $this->installedVersionFromComposer($composer) ?? 'unknown';

        $this->components->info("Laravix CMS upgraded: {$current} → {$new}");

        return self::SUCCESS;
    }

    private function composerCommand(): ?array
    {
        if ($binary = env('COMPOSER_BINARY')) {
            return [$binary];
        }

        if ($path = (new ExecutableFinder)->find('composer')) {
            return [$path];
        }

        if (is_file(base_path('composer.phar'))) {
            return [PHP_BINARY, base_path('composer.phar')];
        }

        return null;
    }

    private function runStreaming(array $command, ?string &$capturedOutput = null): bool
    {
        $buffer = '';

        $this->components->task(implode(' ', array_slice($command, -3)), function () use ($command, &$ok, &$buffer) {
            $process = new Process($command, base_path(), null, null, 600);
            $process->run(function ($type, $chunk) use (&$buffer) {
                $buffer .= $chunk;
                $this->output->write($chunk);
            });

            return $ok = $process->isSuccessful();
        });

        $capturedOutput = $buffer;

        return (bool) $ok;
    }

    private function renderPackageChanges(string $output): void
    {
        preg_match_all('/- (?:Upgrading|Downgrading) (\S+) \(([^)]+) => ([^)]+)\)/', $output, $matches, PREG_SET_ORDER);

        if ($matches === []) {
            return;
        }

        $this->newLine();
        $this->components->info('Package changes');

        foreach ($matches as [, $package, $from, $to]) {
            $color = $package === 'laravix/cms' ? '#ff0465' : 'white';

            $this->line("  <fg={$color}>{$package}</>  <fg=gray>{$from}</> → <fg=green>{$to}</>");
        }

        $this->newLine();
    }

    private function installedVersionFromComposer(array $composer): ?string
    {
        $process = new Process([...$composer, 'show', 'laravix/cms', '--format=json'], base_path(), null, null, 60);
        $process->run();

        if (! $process->isSuccessful()) {
            return null;
        }

        return json_decode($process->getOutput(), true)['versions'][0] ?? null;
    }
}
