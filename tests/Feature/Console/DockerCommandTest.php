<?php

function removeDirectory(string $path): void
{
    if (! is_dir($path)) {
        return;
    }

    foreach (scandir($path) as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }

        $target = $path.'/'.$entry;

        is_dir($target) ? removeDirectory($target) : unlink($target);
    }

    rmdir($path);
}

beforeEach(function () {
    $this->basePath = sys_get_temp_dir().'/laravix-docker-'.uniqid();

    mkdir($this->basePath.'/database', 0755, true);
    file_put_contents($this->basePath.'/.env', "APP_NAME=Laravix\nDB_CONNECTION=sqlite\n");

    $this->app->setBasePath($this->basePath);
});

afterEach(function () {
    removeDirectory($this->basePath);
});

test('it generates a compose file with only the selected services', function () {
    $this->artisan('laravix:docker', [
        '--db' => 'mysql',
        '--no-worker' => true,
        '--no-scheduler' => true,
        '--no-interaction' => true,
    ])->assertSuccessful();

    $compose = file_get_contents($this->basePath.'/compose.yaml');

    expect($compose)
        ->toContain('laravel.test:')
        ->toContain('    mysql:')
        ->not->toContain('meilisearch:')
        ->not->toContain('mailpit:')
        ->not->toContain('redis:')
        ->not->toContain('    worker:')
        ->not->toContain('    scheduler:');
});

test('it wires depends_on and volumes to the selected services', function () {
    $this->artisan('laravix:docker', [
        '--db' => 'mysql',
        '--no-worker' => true,
        '--no-scheduler' => true,
        '--no-interaction' => true,
    ])->assertSuccessful();

    $compose = file_get_contents($this->basePath.'/compose.yaml');

    expect($compose)
        ->toContain("        depends_on:\n            mysql:\n                condition: service_healthy")
        ->toContain("volumes:\n    laravix-mysql:");
});

test('the worker and scheduler are included unless opted out', function () {
    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertSuccessful();

    $compose = file_get_contents($this->basePath.'/compose.yaml');

    expect($compose)
        ->toContain('    worker:')
        ->toContain("command: 'php artisan queue:work")
        ->toContain('    scheduler:')
        ->toContain("command: 'php artisan schedule:work'");
});

test('companion containers never end up in the app depends_on', function () {
    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertSuccessful();

    $compose = file_get_contents($this->basePath.'/compose.yaml');

    expect($compose)
        ->toContain("            mysql:\n                condition: service_healthy")
        ->not->toContain('laravix-worker:')
        ->not->toContain('laravix-scheduler:');
});

test('it writes the php and mysql configuration files', function () {
    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertSuccessful();

    expect($this->basePath.'/docker/php.ini')->toBeReadableFile()
        ->and($this->basePath.'/docker/mysql/custom.cnf')->toBeReadableFile()
        ->and(file_get_contents($this->basePath.'/docker/php.ini'))->toContain('upload_max_filesize = 512M');
});

test('sqlite produces no database container and no volumes', function () {
    $this->artisan('laravix:docker', [
        '--db' => 'sqlite',
        '--no-worker' => true,
        '--no-scheduler' => true,
        '--no-interaction' => true,
    ])->assertSuccessful();

    $compose = file_get_contents($this->basePath.'/compose.yaml');

    expect($compose)
        ->not->toContain('    mysql:')
        ->not->toContain('depends_on:')
        ->not->toContain('volumes:
    laravix-')
        ->and($this->basePath.'/docker/mysql/custom.cnf')->not->toBeReadableFile()
        ->and($this->basePath.'/database/database.sqlite')->toBeReadableFile();
});

test('it points the env file at the container hostnames', function () {
    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertSuccessful();

    $env = file_get_contents($this->basePath.'/.env');

    expect($env)
        ->toContain('DB_CONNECTION=mysql')
        ->toContain('DB_HOST=mysql')
        ->toContain('DB_PORT=3306');

    preg_match('/^APP_PORT=(\d+)$/m', $env, $port);

    expect($port[1] ?? null)->not->toBeNull()
        ->and($env)->toContain('APP_URL=http://localhost'.($port[1] === '80' ? '' : ':'.$port[1]));
});

test('it replaces existing env keys instead of appending duplicates', function () {
    file_put_contents($this->basePath.'/.env', "DB_CONNECTION=sqlite\nDB_HOST=127.0.0.1\n");

    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertSuccessful();

    $env = file_get_contents($this->basePath.'/.env');

    expect(substr_count($env, 'DB_CONNECTION='))->toBe(1)
        ->and(substr_count($env, 'DB_HOST='))->toBe(1)
        ->and($env)->toContain('DB_HOST=mysql');
});

test('it keeps a copy of the previous env file', function () {
    file_put_contents($this->basePath.'/.env', "DB_CONNECTION=sqlite\nAPP_NAME=Mine\n");

    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertSuccessful();

    expect($this->basePath.'/.env.backup')->toBeReadableFile()
        ->and(file_get_contents($this->basePath.'/.env.backup'))->toBe("DB_CONNECTION=sqlite\nAPP_NAME=Mine\n")
        ->and(file_get_contents($this->basePath.'/.env'))->toContain('DB_CONNECTION=mysql');
});

test('it warns before touching an existing env file', function () {
    file_put_contents($this->basePath.'/.env', "APP_NAME=Mine\n");

    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->expectsOutputToContain('.env.backup')
        ->assertSuccessful();
});

test('it writes no backup when there is no env file yet', function () {
    unlink($this->basePath.'/.env');
    file_put_contents($this->basePath.'/.env.example', "APP_NAME=Laravix\n");

    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertSuccessful();

    expect($this->basePath.'/.env')->toBeReadableFile()
        ->and($this->basePath.'/.env.backup')->not->toBeReadableFile();
});

test('it refuses to overwrite an existing compose file', function () {
    file_put_contents($this->basePath.'/compose.yaml', 'services: {}');

    $this->artisan('laravix:docker', ['--db' => 'mysql', '--no-interaction' => true])
        ->assertFailed();

    expect(file_get_contents($this->basePath.'/compose.yaml'))->toBe('services: {}');
});

test('it keeps the previously selected services when regenerating', function () {
    $this->artisan('laravix:docker', ['--db' => 'pgsql', '--redis' => true, '--no-interaction' => true])
        ->assertSuccessful();

    $this->artisan('laravix:docker', ['--force' => true, '--no-interaction' => true])
        ->assertSuccessful();

    expect(file_get_contents($this->basePath.'/compose.yaml'))
        ->toContain('    pgsql:')
        ->toContain('    redis:')
        ->not->toContain('    mysql:');
});

test('every selectable service ships a stub', function (string $service) {
    expect(dirname(__DIR__, 3)."/packages/laravix/cms/stubs/docker/services/{$service}.stub")
        ->toBeReadableFile();
})->with(['mysql', 'pgsql', 'meilisearch', 'mailpit', 'redis', 'worker', 'scheduler']);

test('it composes every service into one valid file', function () {
    $this->artisan('laravix:docker', [
        '--db' => 'mysql',
        '--search' => true,
        '--mail' => true,
        '--redis' => true,
        '--no-interaction' => true,
    ])->assertSuccessful();

    $compose = file_get_contents($this->basePath.'/compose.yaml');

    expect($compose)
        ->toContain('    mysql:')
        ->toContain('    meilisearch:')
        ->toContain('    mailpit:')
        ->toContain('    redis:')
        ->toContain(
            "        depends_on:\n".
            "            mysql:\n                condition: service_healthy\n".
            "            meilisearch:\n                condition: service_healthy\n".
            "            mailpit:\n                condition: service_started\n".
            "            redis:\n                condition: service_healthy\n"
        )
        ->toContain('    laravix-mysql:')
        ->toContain('    laravix-meilisearch:')
        ->toContain('    laravix-redis:')
        ->not->toContain('laravix-mailpit:');

    $env = file_get_contents($this->basePath.'/.env');

    expect($env)
        ->toContain('SCOUT_DRIVER=meilisearch')
        ->toContain('MEILISEARCH_HOST=http://meilisearch:7700')
        ->toContain('MAIL_HOST=mailpit')
        ->toContain('REDIS_HOST=redis')
        ->toContain('CACHE_STORE=redis');
});
