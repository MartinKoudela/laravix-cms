<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Concerns;

use Laravix\Cms\Support\DockerEnvironment;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

trait ConfiguresDockerEnvironment
{
    private function askForDockerEnvironment(): DockerEnvironment
    {
        $existing = $this->detectDockerEnvironment();
        $interactive = ! $this->option('no-interaction');

        $database = $this->option('db') ?: ($interactive
            ? select('Database', [
                'mysql' => 'MySQL 8.4',
                'pgsql' => 'PostgreSQL 17',
                'sqlite' => 'SQLite — no container, file on disk',
            ], default: $existing?->database ?? 'mysql')
            : $existing?->database ?? 'mysql');

        $selected = array_values(array_filter([
            $this->option('search') || $existing?->meilisearch ? 'meilisearch' : null,
            $this->option('mail') || $existing?->mailpit ? 'mailpit' : null,
            $this->option('redis') || $existing?->redis ? 'redis' : null,
            $this->wantsCompanion('worker', $existing?->worker) ? 'worker' : null,
            $this->wantsCompanion('scheduler', $existing?->scheduler) ? 'scheduler' : null,
        ]));

        if ($interactive) {
            $selected = multiselect('Additional services', [
                'meilisearch' => 'Meilisearch — full-text search for the CMS',
                'mailpit' => 'Mailpit — catches outgoing mail, dashboard on :8025',
                'redis' => 'Redis — cache and sessions',
                'worker' => 'Queue worker — generates image variants and other queued work',
                'scheduler' => 'Scheduler — publishes scheduled content every minute',
            ], default: $selected);
        }

        $port = (int) ($this->option('port') ?: ($interactive
            ? text('Host port', default: (string) ($existing?->appPort ?? 80), validate: fn (string $value): ?string => filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]]) === false
                ? 'Enter a port between 1 and 65535.'
                : null)
            : $existing?->appPort ?? 80));

        $environment = new DockerEnvironment(
            database: $database,
            meilisearch: in_array('meilisearch', $selected, true),
            mailpit: in_array('mailpit', $selected, true),
            redis: in_array('redis', $selected, true),
            worker: in_array('worker', $selected, true),
            scheduler: in_array('scheduler', $selected, true),
            appPort: $port,
        );

        return $existing === null ? $this->avoidPortConflicts($environment) : $environment;
    }

    private function wantsCompanion(string $name, ?bool $declared): bool
    {
        if ($this->option("no-{$name}")) {
            return false;
        }

        return $declared ?? true;
    }

    private function avoidPortConflicts(DockerEnvironment $environment): DockerEnvironment
    {
        $resolved = $environment->withResolvedPorts($this->portIsAvailable(...));

        $moved = [];

        if ($resolved->appPort !== $environment->appPort) {
            $moved[] = 'site '.$environment->appPort.' → '.$resolved->appPort;
        }

        if ($resolved->vitePort !== $environment->vitePort) {
            $moved[] = 'vite '.$environment->vitePort.' → '.$resolved->vitePort;
        }

        foreach ($resolved->forwardPortOverrides as $key => $port) {
            $moved[] = strtolower(str_replace(['FORWARD_', '_PORT'], '', $key)).' '.$environment->defaultForwardPorts()[$key].' → '.$port;
        }

        if ($moved !== []) {
            $this->components->warn('Ports already in use, moved: '.implode(', ', $moved).'.');
        }

        return $resolved;
    }

    private function portIsAvailable(int $port): bool
    {
        $socket = @fsockopen('127.0.0.1', $port, $code, $message, 0.3);

        if ($socket === false) {
            return true;
        }

        fclose($socket);

        return false;
    }

    private function detectDockerEnvironment(): ?DockerEnvironment
    {
        $path = base_path('compose.yaml');

        if (! is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);

        $declares = fn (string $service): bool => preg_match('/^ {4}'.preg_quote($service, '/').':\s*$/m', $contents) === 1;

        return new DockerEnvironment(
            database: match (true) {
                $declares('mysql') => 'mysql',
                $declares('pgsql') => 'pgsql',
                default => 'sqlite',
            },
            meilisearch: $declares('meilisearch'),
            mailpit: $declares('mailpit'),
            redis: $declares('redis'),
            worker: $declares('worker'),
            scheduler: $declares('scheduler'),
            appPort: (int) (env('APP_PORT') ?: 80),
            vitePort: (int) (env('VITE_PORT') ?: 5173),
        );
    }

    private function missingDockerStubs(DockerEnvironment $environment): array
    {
        $required = ['compose', 'php.ini'];

        foreach ($environment->containers() as $container) {
            $required[] = "services/{$container}";
        }

        if ($environment->database === 'mysql') {
            $required[] = 'mysql.cnf';
        }

        return array_values(array_filter(
            $required,
            fn (string $name): bool => ! is_file($this->dockerStubPath($name))
        ));
    }

    private function writeDockerFiles(DockerEnvironment $environment): array
    {
        $written = [];

        $this->putDockerFile(base_path('compose.yaml'), $this->composeContents($environment));
        $written[] = 'compose.yaml';

        $this->putDockerFile(base_path('docker/php.ini'), $this->dockerStub('php.ini'));
        $written[] = 'docker/php.ini';

        if ($environment->database === 'mysql') {
            $this->putDockerFile(base_path('docker/mysql/custom.cnf'), $this->dockerStub('mysql.cnf'));
            $written[] = 'docker/mysql/custom.cnf';
        }

        if ($environment->database === 'sqlite') {
            $this->putDockerFile(database_path('database.sqlite'), '');
            $written[] = 'database/database.sqlite';
        }

        return $written;
    }

    private function composeContents(DockerEnvironment $environment): string
    {
        $containers = $environment->containers();
        $dependsOn = $this->dependsOnBlock($environment);

        $services = '';

        foreach ($containers as $container) {
            $services .= PHP_EOL.str_replace('{{ depends_on }}', $dependsOn, $this->dockerStub("services/{$container}"));
        }

        $volumes = $environment->volumes() === [] ? '' : 'volumes:'.PHP_EOL.implode('', array_map(
            fn (string $volume): string => "    {$volume}:".PHP_EOL.'        driver: local'.PHP_EOL,
            $environment->volumes()
        ));

        return str_replace(
            ['{{ depends_on }}', '{{ services }}', '{{ volumes }}'],
            [$dependsOn, $services, $volumes],
            $this->dockerStub('compose')
        );
    }

    private function dependsOnBlock(DockerEnvironment $environment): string
    {
        $dependencies = $environment->dependencies();

        if ($dependencies === []) {
            return '';
        }

        $healthy = $environment->healthchecks();
        $block = '        depends_on:'.PHP_EOL;

        foreach ($dependencies as $service) {
            $condition = in_array($service, $healthy, true) ? 'service_healthy' : 'service_started';

            $block .= "            {$service}:".PHP_EOL."                condition: {$condition}".PHP_EOL;
        }

        return $block;
    }

    private function dockerStub(string $name): string
    {
        return file_get_contents($this->dockerStubPath($name));
    }

    private function dockerStubPath(string $name): string
    {
        return dirname(__DIR__, 3).'/stubs/docker/'.$name.'.stub';
    }

    private function putDockerFile(string $path, string $contents): void
    {
        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if ($contents === '' && is_file($path)) {
            return;
        }

        file_put_contents($path, $contents);
    }
}
