<?php

use Laravix\Cms\Support\DockerEnvironment;

test('it keeps the preferred ports when they are all free', function () {
    $environment = new DockerEnvironment(database: 'mysql', meilisearch: true, appPort: 80);

    $resolved = $environment->withResolvedPorts(fn (): bool => true);

    expect($resolved->appPort)->toBe(80)
        ->and($resolved->forwardPortOverrides)->toBe([])
        ->and($resolved->forwardPorts())->toBe([
            'FORWARD_DB_PORT' => 3306,
            'FORWARD_MEILISEARCH_PORT' => 7700,
        ]);
});

test('it moves to the next free port when the preferred one is taken', function () {
    $taken = [80, 3306, 3307];

    $environment = new DockerEnvironment(database: 'mysql', appPort: 80);

    $resolved = $environment->withResolvedPorts(fn (int $port): bool => ! in_array($port, $taken, true));

    expect($resolved->appPort)->toBe(81)
        ->and($resolved->forwardPorts()['FORWARD_DB_PORT'])->toBe(3308);
});

test('it never hands the same port to two services', function () {
    $taken = [5172];

    $environment = new DockerEnvironment(database: 'mysql', appPort: 5172, vitePort: 5173);

    $resolved = $environment->withResolvedPorts(fn (int $port): bool => ! in_array($port, $taken, true));

    expect($resolved->appPort)->toBe(5173)
        ->and($resolved->vitePort)->toBe(5174);
});

test('the vite port is moved when it is taken', function () {
    $environment = new DockerEnvironment(database: 'sqlite', appPort: 80, vitePort: 5173);

    $resolved = $environment->withResolvedPorts(fn (int $port): bool => $port !== 5173);

    expect($resolved->appPort)->toBe(80)
        ->and($resolved->vitePort)->toBe(5174)
        ->and($resolved->environmentValues()['VITE_PORT'])->toBe(5174);
});

test('resolved ports land in the env file', function () {
    $environment = (new DockerEnvironment(database: 'mysql', appPort: 80))
        ->withResolvedPorts(fn (int $port): bool => $port !== 3306);

    $values = $environment->environmentValues();

    expect($values['FORWARD_DB_PORT'])->toBe(3307)
        ->and($values['DB_PORT'])->toBe(3306);
});

test('sqlite exposes no forwarded ports', function () {
    $environment = new DockerEnvironment(database: 'sqlite');

    expect($environment->defaultForwardPorts())->toBe([])
        ->and($environment->containers())->toBe([]);
});
