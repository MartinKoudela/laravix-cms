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
use Laravix\Cms\Console\Concerns\RendersBanner;
use Laravix\Cms\Database\Seeders\DemoSeeder;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Symfony\Component\Console\Terminal;
use Throwable;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

#[Signature('laravix:install
    {--demo : Seed demo content into the first site}
    {--force : Run even when sites already exist}
    {--site-name= : Name of the first site}
    {--domain= : Domain of the first site}
    {--admin-name= : Name of the super admin}
    {--admin-email= : Email of the super admin}
    {--admin-password= : Password of the super admin}')]
#[Description('Interactive first-run setup: database, assets, first site and super admin.')]
class Install extends Command
{
    use RendersBanner;

    public function handle(): int
    {
        $startedAt = microtime(true);

        $this->renderBanner();

        $this->components->info('Laravix CMS installer');

        if (! $this->setUpDatabase()) {
            return self::FAILURE;
        }

        if (! $this->guardAgainstExistingInstallation()) {
            return self::FAILURE;
        }

        $this->call('migrate', ['--force' => true]);
        $this->components->task('Linking storage', fn () => $this->callSilently('storage:link') === self::SUCCESS);

        $this->publishAssets();

        $site = $this->createFirstSite();
        $admin = $this->createSuperAdmin();

        if ($admin === null) {
            return self::FAILURE;
        }

        if ($this->option('demo') || (! $this->option('no-interaction') && confirm('Seed demo content?', default: true))) {
            spin(fn () => app(DemoSeeder::class)->run($site, $admin), 'Seeding demo content...');
            info('Demo content created.');
        } else {
            $this->components->warn('Demo content skipped.');
        }

        $this->renderSummary($site, $admin, $startedAt);

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

    private function writeEnv(array $values): void
    {
        $path = base_path('.env');
        $env = file_get_contents($path);

        foreach ($values as $key => $value) {
            $line = $key.'='.(preg_match('/\s/', (string) $value) ? '"'.$value.'"' : $value);

            $env = preg_match("/^{$key}=.*/m", $env)
                ? preg_replace("/^{$key}=.*/m", $line, $env)
                : $env.PHP_EOL.$line;
        }

        file_put_contents($path, $env);
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
