<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Console\Commands;

use Dotenv\Dotenv;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Laravix\Cms\Console\Concerns\ConfiguresDockerEnvironment;
use Laravix\Cms\Console\Concerns\CtaReference;
use Laravix\Cms\Console\Concerns\InteractsWithContainers;
use Laravix\Cms\Console\Concerns\RendersBanner;
use Laravix\Cms\Console\Concerns\WritesEnvFile;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Symfony\Component\Console\Terminal;
use Symfony\Component\Process\Process;
use Throwable;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[Signature('laravix:install
    {--force : Run even when sites already exist}
    {--docker : Generate a Docker environment and install inside it}
    {--db= : Database service when installing with Docker: mysql, pgsql or sqlite}
    {--search : Add a Meilisearch container}
    {--mail : Add a Mailpit container}
    {--redis : Add a Redis container}
    {--no-worker : Leave out the queue worker container}
    {--no-scheduler : Leave out the scheduler container}
    {--port= : Host port the site is served on}
    {--continue= : Internal: resume the installation inside the container from a state file}
    {--site-name= : Name of the first site}
    {--domain= : Domain of the first site}
    {--admin-name= : Name of the super admin}
    {--admin-email= : Email of the super admin}
    {--admin-password= : Password of the super admin}')]
#[Description('Interactive first-run setup: database, assets, first site and super admin.')]
class Install extends Command
{
    use ConfiguresDockerEnvironment;
    use CtaReference;
    use InteractsWithContainers;
    use RendersBanner;
    use WritesEnvFile;

    public function handle(): int
    {
        $startedAt = microtime(true);

        $this->renderBanner();

        $this->components->info('Laravix CMS installer');

        $this->applyResumeState();

        if ($this->shouldRunInDocker()) {
            return $this->runInDocker();
        }

        if (! $this->setUpDatabase()) {
            return self::FAILURE;
        }

        if (! $this->guardAgainstExistingInstallation()) {
            return self::FAILURE;
        }

        $this->call('migrate', ['--force' => true]);
        $this->components->task('Linking storage', fn () => $this->callSilently('storage:link') === self::SUCCESS);
        $this->components->task('Linking themes', fn () => $this->callSilently('laravix:theme:link') === self::SUCCESS);

        $this->publishAssets();

        $site = $this->createFirstSite();
        $admin = $this->createSuperAdmin();

        if ($admin === null) {
            return self::FAILURE;
        }

        $this->renderSummary($site, $admin, $startedAt);

        if (! $this->option('no-interaction')) {
            $this->offerPromo();
        }

        return self::SUCCESS;
    }

    private function renderSummary(Site $site, User $admin, float $startedAt): void
    {
        $elapsed = round(microtime(true) - $startedAt, 1);
        $rule = str_repeat('━', min((new Terminal)->getWidth(), 60));

        $this->newLine();
        $this->line("<fg=#ff0465>{$rule}</>");
        $this->line("  <options=bold>Laravix CMS is installed</>  <fg=#888888>{$elapsed}s</>");
        $this->newLine();
        $this->line('  Site          '.$this->hyperlink('https://'.$site->domain));
        $this->line('  Admin panel   '.$this->hyperlink(url('/admin')));
        $this->line('  Login         '.$admin->email);
        $this->newLine();
        $this->line("<fg=#ff0465>{$rule}</>");
        $this->newLine();
    }

    private function setUpDatabase(): bool
    {
        if ($this->databaseIsReachable()) {
            return true;
        }

        if ($this->option('no-interaction')) {
            $this->components->error('Database connection failed. Configure the database in .env and run the installer again.');

            return false;
        }

        $driver = select('Database', [
            'sqlite' => 'SQLite (recommended to start)',
            'mysql' => 'MySQL / MariaDB',
            'pgsql' => 'PostgreSQL',
        ], default: 'sqlite');

        if ($driver === 'sqlite') {
            $this->writeEnv(['DB_CONNECTION' => 'sqlite']);
            touch(database_path('database.sqlite'));
        } else {
            $this->writeEnv([
                'DB_CONNECTION' => $driver,
                'DB_HOST' => text('Database host', default: '127.0.0.1'),
                'DB_PORT' => text('Database port', default: $driver === 'mysql' ? '3306' : '5432'),
                'DB_DATABASE' => text('Database name', required: true),
                'DB_USERNAME' => text('Database user', required: true),
                'DB_PASSWORD' => text('Database password', default: ''),
            ]);
        }

        $this->refreshDatabaseConnection($driver);

        if (! $this->databaseIsReachable()) {
            $this->components->error('Could not connect to the database with the given credentials.');

            return false;
        }

        return true;
    }

    private function databaseIsReachable(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private function refreshDatabaseConnection(string $driver): void
    {
        config(['database.default' => $driver]);

        foreach (Dotenv::parse(file_get_contents(base_path('.env'))) as $key => $value) {
            match ($key) {
                'DB_HOST' => config(["database.connections.{$driver}.host" => $value]),
                'DB_PORT' => config(["database.connections.{$driver}.port" => $value]),
                'DB_DATABASE' => config(["database.connections.{$driver}.database" => $driver === 'sqlite' ? database_path('database.sqlite') : $value]),
                'DB_USERNAME' => config(["database.connections.{$driver}.username" => $value]),
                'DB_PASSWORD' => config(["database.connections.{$driver}.password" => $value]),
                default => null,
            };
        }

        if ($driver === 'sqlite') {
            config(['database.connections.sqlite.database' => database_path('database.sqlite')]);
        }

        DB::purge();
    }

    private function shouldRunInDocker(): bool
    {
        if ($this->option('continue') || env('LARAVEL_SAIL')) {
            return false;
        }

        if ($this->option('docker')) {
            return true;
        }

        if ($this->option('no-interaction') || is_file(base_path('compose.yaml'))) {
            return false;
        }

        return select('How do you want to run Laravix?', [
            'docker' => 'Docker — database and services in containers',
            'local' => 'Local services — I already have a database running',
        ], default: 'docker') === 'docker';
    }

    private function runInDocker(): int
    {
        $environment = $this->askForDockerEnvironment();

        if ($missing = $this->missingDockerStubs($environment)) {
            $this->components->error('Missing stubs: '.implode(', ', $missing).'. This service is not supported yet.');

            return self::FAILURE;
        }

        $state = [
            'site-name' => $this->option('site-name') ?: text('Site name', required: true),
            'domain' => $this->option('domain') ?: text('Site domain', placeholder: 'example.com', required: true),
            'admin-name' => $this->option('admin-name') ?: text('Admin name', required: true),
            'admin-email' => $this->option('admin-email') ?: text('Admin email', required: true, validate: fn (string $value): ?string => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email address.'),
            'admin-password' => $this->option('admin-password') ?: password('Admin password', required: true, validate: fn (string $value): ?string => strlen($value) >= 8 ? null : 'Password must be at least 8 characters.'),
        ];

        foreach ($this->writeDockerFiles($environment) as $file) {
            $this->components->task($file);
        }

        $backup = $this->writeEnv($environment->environmentValues());
        $this->components->task('.env');

        if ($backup !== null) {
            $this->components->task($backup);
        }

        $this->ensureApplicationKey();

        if (! $this->startContainers() || ! $this->waitForContainers($environment)) {
            return self::FAILURE;
        }

        $exitCode = $this->continueInContainer($state);

        if ($exitCode === self::SUCCESS && ! $this->option('no-interaction')) {
            $this->offerPromo();
        }

        return $exitCode;
    }

    private function ensureApplicationKey(): void
    {
        if (config('app.key')) {
            return;
        }

        $this->components->task('Generating application key', fn (): bool => $this->callSilently('key:generate') === self::SUCCESS);
    }

    private function continueInContainer(array $state): int
    {
        $relativePath = 'storage/app/private/laravix-install.json';
        $path = base_path($relativePath);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($state));
        chmod($path, 0600);

        $command = $this->artisanInContainerCommand(array_values(array_filter([
            'laravix:install',
            '--continue='.$relativePath,
            '--no-interaction',
            $this->option('force') ? '--force' : null,
        ])));

        if ($command === null) {
            unlink($path);

            $this->components->error('Could not find a way to run artisan inside the container.');

            return self::FAILURE;
        }

        try {
            $process = new Process($command, base_path(), null, null, null);
            $process->run(fn (string $type, string $chunk) => $this->output->write($chunk));

            return $process->isSuccessful() ? self::SUCCESS : self::FAILURE;
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    private function applyResumeState(): void
    {
        $option = $this->option('continue');

        if (! $option) {
            return;
        }

        $path = str_starts_with($option, DIRECTORY_SEPARATOR) ? $option : base_path($option);

        if (! is_file($path)) {
            return;
        }

        $state = json_decode(file_get_contents($path), true);

        unlink($path);

        if (! is_array($state)) {
            return;
        }

        foreach (['site-name', 'domain', 'admin-name', 'admin-email', 'admin-password'] as $key) {
            if (! empty($state[$key]) && ! $this->option($key)) {
                $this->input->setOption($key, $state[$key]);
            }
        }
    }

    private function guardAgainstExistingInstallation(): bool
    {
        try {
            if (Site::exists() && ! $this->option('force')) {
                $this->components->error('Sites already exist — this application looks installed. Use --force to run anyway.');

                return false;
            }
        } catch (Throwable) {
        }

        return true;
    }

    private function publishAssets(): void
    {
        $this->components->task('Publishing assets', function () {
            $this->callSilently('vendor:publish', ['--tag' => 'laravix-assets', '--force' => true]);
            $this->callSilently('vendor:publish', ['--tag' => 'laravix-config']);
            $this->callSilently('vendor:publish', ['--tag' => 'laravix-views']);
            $this->callSilently('filament:assets');

            return true;
        });

        if (is_dir(base_path('themes/default'))) {
            $this->components->warn('Theme already published — skipping.');
        } else {
            $this->components->task('Publishing default theme', function () {
                $this->callSilently('vendor:publish', ['--tag' => 'laravix-theme']);

                return true;
            });
        }
    }

    private function createFirstSite(): Site
    {
        $name = $this->option('site-name') ?: text('Site name', required: true);
        $domain = $this->option('domain') ?: text('Site domain', placeholder: 'example.com', required: true);

        $site = Site::create([
            'name' => $name,
            'domain' => $domain,
            'mode' => 'theme',
            'theme' => 'default',
        ]);

        info("Site {$site->name} ({$site->domain}) created.");

        return $site;
    }

    private function createSuperAdmin(): ?User
    {
        $exit = $this->call('laravix:user', [
            '--name' => $this->option('admin-name'),
            '--email' => $this->option('admin-email'),
            '--password' => $this->option('admin-password'),
            '--super' => true,
        ]);

        if ($exit !== self::SUCCESS) {
            return null;
        }

        return User::where('is_super_admin', true)->latest('id')->first();
    }
}
