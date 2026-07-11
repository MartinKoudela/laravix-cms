<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravix\Cms\Services\UpdateChecker;

beforeEach(function () {
    Cache::forget('laravix.latest-version');
});

test('latest version picks the highest stable release', function () {
    Http::fake([
        'repo.packagist.org/*' => Http::response([
            'packages' => [
                'laravix/cms' => [
                    ['version' => 'v0.9.0'],
                    ['version' => 'v0.10.0'],
                    ['version' => 'v0.10.1-beta.1'],
                    ['version' => 'dev-main'],
                    ['version' => 'v0.2.0'],
                ],
            ],
        ]),
    ]);

    expect(app(UpdateChecker::class)->latestVersion())->toBe('0.10.0');
});

test('check can be disabled by config', function () {
    config(['laravix.updates.check' => false]);
    Http::fake();

    expect(app(UpdateChecker::class)->latestVersion())->toBeNull();
    Http::assertNothingSent();
});

test('network failure is swallowed', function () {
    Http::fake(fn () => throw new ConnectionException('offline'));

    expect(app(UpdateChecker::class)->latestVersion())->toBeNull();
});

test('update is not offered on dev installations', function () {
    Http::fake([
        'repo.packagist.org/*' => Http::response([
            'packages' => ['laravix/cms' => [['version' => 'v99.0.0']]],
        ]),
    ]);

    expect(Laravix\Cms\Laravix::version())->toStartWith('dev')
        ->and(app(UpdateChecker::class)->updateAvailable())->toBeFalse();
});
