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
use Illuminate\Support\Facades\Process;
use Laravix\Cms\Laravix;
use Laravix\Cms\Services\UpdateChecker;
use Symfony\Component\Process\ExecutableFinder;

use function Laravel\Prompts\confirm;

#[Signature('laravix:upgrade {--force : Skip the confirmation prompt}')]
#[Description('Upgrade the Laravix CMS core: composer update, migrations and asset republish.')]
class Upgrade extends Command
{
    public function handle(UpdateChecker $updateChecker): int
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
        $arguments = $this->composerArguments($current, $updateChecker->latestVersion());

        if ($arguments[0] === 'require') {
            $this->components->info("Raising the composer.json constraint to {$arguments[1]}.");
        }

        if (! $this->runStreaming([...$composer, ...$arguments], $composerOutput)) {
            $installed = $this->installedVersionFromComposer($composer);

            if ($installed === null || $this->isSameVersion($installed, $current)) {
                $this->components->error('composer update failed — nothing else was touched.');

                return self::FAILURE;
            }

            $this->components->warn("Composer scripts reported an error, but laravix/cms is now {$installed} — finishing the upgrade.");
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

    public function composerArguments(string $current, ?string $latest): array
    {
        if ($latest !== null && ! str_starts_with($current, 'dev')
            && version_compare(ltrim($latest, 'v'), ltrim($current, 'v'), '>')) {
            return ['require', 'laravix/cms:^'.ltrim($latest, 'v'), '--with-all-dependencies', '--no-interaction'];
        }

        return ['update', 'laravix/cms', '--with-all-dependencies', '--no-interaction'];
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
        $ok = false;

        $this->components->task(implode(' ', array_slice($command, -3)), function () use ($command, &$ok, &$capturedOutput) {
            $result = Process::path(base_path())->timeout(600)->run(
                $command,
                fn (string $type, string $chunk) => $this->output->write($chunk),
            );

            $capturedOutput = $result->output().$result->errorOutput();

            return $ok = $result->successful();
        });

        return $ok;
    }

    private function isSameVersion(string $left, string $right): bool
    {
        return version_compare(ltrim($left, 'v'), ltrim($right, 'v'), '==');
    }

    private function renderPackageChanges(?string $output): void
    {
        preg_match_all('/- (?:Upgrading|Downgrading) (\S+) \(([^)]+) => ([^)]+)\)/', (string) $output, $matches, PREG_SET_ORDER);

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
        $result = Process::path(base_path())->timeout(60)
            ->run([...$composer, 'show', 'laravix/cms', '--format=json']);

        if (! $result->successful()) {
            return null;
        }

        return json_decode($result->output(), true)['versions'][0] ?? null;
    }
}
