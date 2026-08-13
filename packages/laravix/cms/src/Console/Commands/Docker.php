<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Laravix\Cms\Console\Concerns\ConfiguresDockerEnvironment;
use Laravix\Cms\Console\Concerns\InteractsWithContainers;
use Laravix\Cms\Console\Concerns\WritesEnvFile;
use Laravix\Cms\Support\DockerEnvironment;

#[Signature('laravix:docker
    {--db= : Database service to run: mysql, pgsql or sqlite}
    {--search : Add a Meilisearch container}
    {--mail : Add a Mailpit container}
    {--redis : Add a Redis container}
    {--no-worker : Leave out the queue worker container}
    {--no-scheduler : Leave out the scheduler container}
    {--port= : Host port the site is served on}
    {--force : Overwrite an existing compose.yaml}')]
#[Description('Generate a Docker environment (compose.yaml, docker/, .env) for local development.')]
class Docker extends Command
{
    use ConfiguresDockerEnvironment;
    use InteractsWithContainers;
    use WritesEnvFile;

    public function handle(): int
    {
        $exists = is_file(base_path('compose.yaml'));

        if ($exists && ! $this->option('force')) {
            $this->components->error('compose.yaml already exists. Rerun with --force to regenerate it.');

            return self::FAILURE;
        }

        if ($exists) {
            $this->components->warn('compose.yaml will be overwritten — any manual edits to it are lost.');
        }

        if (is_file(base_path('.env'))) {
            $this->components->warn('.env will be rewritten with the container hostnames and ports. A copy is kept as .env.backup.');
        }

        $environment = $this->askForDockerEnvironment();

        if ($missing = $this->missingDockerStubs($environment)) {
            $this->components->error('Missing stubs: '.implode(', ', $missing).'. This service is not supported yet.');

            return self::FAILURE;
        }

        foreach ($this->writeDockerFiles($environment) as $file) {
            $this->components->task($file);
        }

        $backup = $this->writeEnv($environment->environmentValues());
        $this->components->task('.env');

        if ($backup !== null) {
            $this->components->task($backup);
        }

        $this->renderNextSteps($environment);

        return self::SUCCESS;
    }

    private function renderNextSteps(DockerEnvironment $environment): void
    {
        $prefix = $this->containerCommandPrefix();

        $this->newLine();
        $this->components->info('Next steps');
        $this->line('  <fg=#ff0465>1.</>  '.($this->sailScript() ? './vendor/bin/sail up -d' : 'docker compose up -d'));
        $this->line("  <fg=#ff0465>2.</>  {$prefix} artisan laravix:install");
        $this->newLine();

        $this->line('  Site        http://localhost'.($environment->appPort === 80 ? '' : ':'.$environment->appPort));

        if ($environment->mailpit) {
            $this->line('  Mailpit     http://localhost:8025');
        }

        if ($environment->meilisearch) {
            $this->line('  Meilisearch http://localhost:7700');
        }

        $this->newLine();

        if ($environment->meilisearch) {
            $this->components->warn('Scout now points at Meilisearch. Run `'.$prefix.' artisan scout:import "Laravix\\Cms\\Models\\Content"` if you already have content.');
        }
    }
}
