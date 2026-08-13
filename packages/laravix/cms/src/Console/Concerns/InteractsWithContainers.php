<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Concerns;

use Laravix\Cms\Support\DockerEnvironment;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

trait InteractsWithContainers
{
    private function sailScript(): ?string
    {
        $path = base_path('vendor/bin/sail');

        return is_file($path) ? $path : null;
    }

    private function dockerComposeBinary(): ?array
    {
        $finder = new ExecutableFinder;

        if ($docker = $finder->find('docker')) {
            return [$docker, 'compose'];
        }

        if ($legacy = $finder->find('docker-compose')) {
            return [$legacy];
        }

        return null;
    }

    private function containerCommandPrefix(): string
    {
        return $this->sailScript() !== null ? './vendor/bin/sail' : 'docker compose exec laravel.test php';
    }

    private function startContainersCommand(): ?array
    {
        if ($sail = $this->sailScript()) {
            return [$sail, 'up', '-d'];
        }

        if ($docker = $this->dockerComposeBinary()) {
            return [...$docker, 'up', '-d'];
        }

        return null;
    }

    private function artisanInContainerCommand(array $arguments): ?array
    {
        if ($sail = $this->sailScript()) {
            return [$sail, 'artisan', ...$arguments];
        }

        if ($docker = $this->dockerComposeBinary()) {
            return [...$docker, 'exec', '-T', 'laravel.test', 'php', 'artisan', ...$arguments];
        }

        return null;
    }

    private function startContainers(): bool
    {
        $command = $this->startContainersCommand();

        if ($command === null) {
            $this->components->error('Neither vendor/bin/sail nor the docker binary was found. Install Docker Desktop and run composer install.');

            return false;
        }

        $started = false;
        $output = '';

        $this->components->task('Starting containers', function () use ($command, &$started, &$output): bool {
            $process = new Process($command, base_path(), null, null, null);
            $process->run(function (string $type, string $chunk) use (&$output): void {
                $output .= $chunk;
            });

            return $started = $process->isSuccessful();
        });

        if (! $started) {
            $this->newLine();
            $this->output->write($output);
            $this->components->error('Containers failed to start. Fix the error above, then run: '.$this->containerCommandPrefix().' artisan laravix:install');
        }

        return $started;
    }

    private function waitForContainers(DockerEnvironment $environment, int $timeout = 180): bool
    {
        $expected = $environment->healthchecks();

        if ($expected === []) {
            return true;
        }

        $deadline = time() + $timeout;
        $healthy = false;

        $this->components->task('Waiting for '.implode(', ', $expected), function () use ($expected, $deadline, &$healthy): bool {
            while (time() < $deadline) {
                if ($this->containersAreHealthy($expected)) {
                    return $healthy = true;
                }

                sleep(2);
            }

            return false;
        });

        if (! $healthy) {
            $this->components->error('Containers did not become healthy within '.$timeout.'s. Check `docker compose ps`, then run: '.$this->containerCommandPrefix().' artisan laravix:install');
        }

        return $healthy;
    }

    private function containersAreHealthy(array $expected): bool
    {
        $states = $this->containerHealthStates();

        foreach ($expected as $service) {
            if (($states[$service] ?? null) !== 'healthy') {
                return false;
            }
        }

        return true;
    }

    private function containerHealthStates(): array
    {
        $command = $this->dockerComposeBinary();

        if ($command === null) {
            return [];
        }

        $process = new Process([...$command, 'ps', '--format', 'json'], base_path(), null, null, 30);
        $process->run();

        if (! $process->isSuccessful()) {
            return [];
        }

        $states = [];

        foreach (preg_split('/\R/', trim($process->getOutput())) ?: [] as $line) {
            if (trim($line) === '') {
                continue;
            }

            $decoded = json_decode($line, true);

            if (! is_array($decoded)) {
                continue;
            }

            foreach (array_is_list($decoded) ? $decoded : [$decoded] as $row) {
                if (is_array($row) && isset($row['Service'])) {
                    $states[$row['Service']] = $row['Health'] ?? '';
                }
            }
        }

        return $states;
    }
}
