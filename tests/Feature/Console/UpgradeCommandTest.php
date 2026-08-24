<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Laravix\Cms\Console\Commands\Upgrade;
use Laravix\Cms\Laravix;

beforeEach(function () {
    Cache::forget('laravix.latest-version');
    putenv('COMPOSER_BINARY=/usr/bin/composer-test');
    Process::preventStrayProcesses();
});

afterEach(function () {
    putenv('COMPOSER_BINARY');
});

function fakeComposerShow(string $version): string
{
    return json_encode(['versions' => [$version]]);
}

test('a newer release raises the composer.json constraint instead of a plain update', function () {
    expect(app(Upgrade::class)->composerArguments('v0.11.1', '0.13.0'))
        ->toBe(['require', 'laravix/cms:^0.13.0', '--with-all-dependencies', '--no-interaction']);
});

test('composer update is used when the constraint does not need raising', function (string $current, ?string $latest) {
    expect(app(Upgrade::class)->composerArguments($current, $latest))
        ->toBe(['update', 'laravix/cms', '--with-all-dependencies', '--no-interaction']);
})->with([
    'already on the latest release' => ['v0.13.0', '0.13.0'],
    'latest release is unknown' => ['v0.11.1', null],
    'development installation' => ['dev-main', '0.13.0'],
]);

test('a failing composer script does not abort the upgrade when the package moved', function () {
    Http::fake(['repo.packagist.org/*' => Http::response([
        'packages' => ['laravix/cms' => [['version' => 'v0.13.0']]],
    ])]);

    Process::fake([
        '*composer-test*show*' => Process::result(fakeComposerShow('v0.13.0')),
        '*composer-test*' => Process::result(output: 'boost:update failed', exitCode: 1),
        '*' => Process::result(''),
    ]);

    $this->artisan('laravix:upgrade', ['--force' => true])
        ->expectsOutputToContain('laravix/cms is now v0.13.0')
        ->assertSuccessful();

    Process::assertRan(fn ($process) => in_array('migrate', $process->command, true));
    Process::assertRan(fn ($process) => in_array('optimize:clear', $process->command, true));
});

test('the upgrade stops before migrating when composer leaves the version untouched', function () {
    Http::fake(['repo.packagist.org/*' => Http::response([
        'packages' => ['laravix/cms' => [['version' => 'v0.13.0']]],
    ])]);

    Process::fake([
        '*composer-test*show*' => Process::result(fakeComposerShow(Laravix::version())),
        '*composer-test*' => Process::result(output: 'could not resolve', exitCode: 1),
        '*' => Process::result(''),
    ]);

    $this->artisan('laravix:upgrade', ['--force' => true])
        ->expectsOutputToContain('composer update failed')
        ->assertFailed();

    Process::assertNotRan(fn ($process) => in_array('migrate', $process->command, true));
});
